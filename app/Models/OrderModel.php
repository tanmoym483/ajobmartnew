<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'ajobmart_orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'order_number',
        'user_id',
        'address_id',
        'subtotal',
        'shipping_charge',
        'actual_shipping',
        'cashback_deduct',
        'platform_fee',
        'included_shipping',
        'total_amount',
        'payment_method',
        'payment_gateway',
        'payment_status',
        'order_status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'stock_deducted',
        'stock_restored',
    ];

    protected $useTimestamps = true;

    /**
     * Create order, insert items, insert shipments
     * and deduct stock in ONE transaction.
     */
    public function createOrderWithItems(
        array $orderData,
        array $cartItems,
        array $shipments = []
    ): int|false {

        $db = $this->db;

        $db->transBegin();

        try {

            if (empty($cartItems)) {
                throw new \RuntimeException('Order items are empty.');
            }

            // Initialize stock tracking.
            $orderData['stock_deducted'] = 0;
            $orderData['stock_restored'] = 0;

            // 1. CREATE ORDER
            if (!$this->insert($orderData)) {
                throw new \RuntimeException('Order insert failed.');
            }

            $orderId = (int) $this->getInsertID();

            if ($orderId <= 0) {
                throw new \RuntimeException('Invalid order ID.');
            }

            // 2. INSERT ORDER ITEMS
            foreach ($cartItems as $item) {

                $productId = (int) ($item['product_id'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);
                $price = (float) ($item['listing_price'] ?? 0);
                $allowance = (float) ($item['shipping_allowance'] ?? 0);

                if (
                    $productId <= 0 ||
                    $quantity <= 0 ||
                    $price < 0 ||
                    $allowance < 0
                ) {
                    throw new \RuntimeException('Invalid order item.');
                }

                $inserted = $db->table('ajobmart_order_items')
                    ->insert([
                        'order_id' => $orderId,
                        'product_id' => $productId,
                        'product_name' => (string) (
                            $item['product_name'] ?? 'Product'
                        ),
                        'product_image' => (string) (
                            $item['image_path'] ?? ''
                        ),
                        'quantity' => $quantity,
                        'unit_price' => $price,
                        'unit_shipping_allowance' => $allowance,
                        'line_total' => round($price * $quantity, 2),
                    ]);

                if (!$inserted) {
                    throw new \RuntimeException(
                        'Unable to insert order item for product ID: ' . $productId
                    );
                }
            }

            // 3. DEDUCT PRODUCT STOCK
            foreach ($cartItems as $item) {

                $productId = (int) $item['product_id'];
                $quantity = (int) $item['quantity'];

                // Atomic deduction: stock cannot become negative.
                $db->table('ajobmart_products')
                    ->set(
                        'stock_quantity',
                        'stock_quantity - ' . $quantity,
                        false
                    )
                    ->set('updated_at', date('Y-m-d H:i:s'))
                    ->where('id', $productId)
                    ->where('stock_quantity >=', $quantity)
                    ->update();

                if ($db->affectedRows() !== 1) {
                    throw new \RuntimeException(
                        'Insufficient stock for product ID: ' . $productId
                    );
                }
            }

            // 4. MARK STOCK AS DEDUCTED
            $updated = $db->table('ajobmart_orders')
                ->where('id', $orderId)
                ->update([
                    'stock_deducted' => 1
                ]);

            if (!$updated) {
                throw new \RuntimeException(
                    'Unable to update stock deduction status.'
                );
            }

            // 5. INSERT SELLER SHIPMENTS
            if (!empty($shipments)) {

                foreach ($shipments as $shipment) {

                    $inserted = $db->table('ajobmart_order_shipments')
                        ->insert([
                            'order_id' => $orderId,
                            'seller_id' => (int) $shipment['seller_id'],
                            'origin_pincode' => $shipment['origin_pincode'],
                            'destination_pincode' => $shipment['destination_pincode'],
                            'weight_grams' => (int) $shipment['weight_grams'],
                            'item_quantity' => (int) $shipment['item_quantity'],
                            'courier_code' => $shipment['courier_code'],
                            'courier_name' => $shipment['courier_name'],
                            'zone_code' => $shipment['zone_code'],
                            'actual_shipping' => $shipment['actual_shipping'],
                            'shipping_allowance' => $shipment['shipping_allowance'],
                            'customer_shipping' => $shipment['customer_shipping'],
                        ]);

                    if (!$inserted) {
                        throw new \RuntimeException(
                            'Unable to insert seller shipment.'
                        );
                    }
                }
            }

            // 6. CHECK AND COMMIT
            if ($db->transStatus() === false) {
                throw new \RuntimeException('Order transaction failed.');
            }

            $db->transCommit();

            return $orderId;

        } catch (\Throwable $e) {

            $db->transRollback();

            log_message(
                'error',
                'Create Ajobmart order failed: ' . $e->getMessage()
            );

            return false;
        }
    }

    /**
     * Restore stock for a cancelled, unpaid order.
     *
     * The order status and stock restoration are updated
     * in the same transaction.
     */
    public function cancelUnpaidOrderAndRestoreStock(
        int $orderId,
        string $paymentStatus = 'failed'
    ): bool {

        $db = $this->db;

        $db->transBegin();

        try {

            $order = $db->query(
                "SELECT id, payment_status, order_status,
                        stock_deducted, stock_restored
                 FROM ajobmart_orders
                 WHERE id = ?
                 FOR UPDATE",
                [$orderId]
            )->getRowArray();

            if (!$order) {
                throw new \RuntimeException('Order not found.');
            }

            // Never use this method for paid orders.
            if (strtolower((string) $order['payment_status']) === 'paid') {
                throw new \RuntimeException(
                    'Paid orders require a separate refund and cancellation process.'
                );
            }

            // Already cancelled: no second restoration.
            if (strtolower((string) $order['order_status']) === 'cancelled') {

                if ((int) $order['stock_restored'] === 1) {
                    $db->transCommit();
                    return true;
                }
            }

            // Restore stock only if it was deducted.
            if (
                (int) $order['stock_deducted'] === 1 &&
                (int) $order['stock_restored'] === 0
            ) {

                $items = $db->table('ajobmart_order_items')
                    ->where('order_id', $orderId)
                    ->get()
                    ->getResultArray();

                if (empty($items)) {
                    throw new \RuntimeException('Order items not found.');
                }

                foreach ($items as $item) {

                    $productId = (int) $item['product_id'];
                    $quantity = (int) $item['quantity'];

                    if ($productId <= 0 || $quantity <= 0) {
                        throw new \RuntimeException('Invalid order item.');
                    }

                    $db->table('ajobmart_products')
                        ->set(
                            'stock_quantity',
                            'stock_quantity + ' . $quantity,
                            false
                        )
                        ->set('updated_at', date('Y-m-d H:i:s'))
                        ->where('id', $productId)
                        ->update();

                    if ($db->affectedRows() !== 1) {
                        throw new \RuntimeException(
                            'Unable to restore stock for product ID: ' . $productId
                        );
                    }
                }

                $restored = $db->table('ajobmart_orders')
                    ->where('id', $orderId)
                    ->where('stock_restored', 0)
                    ->update([
                        'stock_restored' => 1
                    ]);

                if (!$restored) {
                    throw new \RuntimeException(
                        'Unable to mark stock as restored.'
                    );
                }
            }

            // Cancel the order.
            $cancelled = $db->table('ajobmart_orders')
                ->where('id', $orderId)
                ->update([
                    'order_status' => 'cancelled',
                    'payment_status' => $paymentStatus,
                ]);

            if (!$cancelled || $db->transStatus() === false) {
                throw new \RuntimeException('Unable to cancel order.');
            }

            $db->transCommit();

            return true;

        } catch (\Throwable $e) {

            $db->transRollback();

            log_message(
                'error',
                'Order cancellation / stock restoration failed for order '
                . $orderId . ': ' . $e->getMessage()
            );

            return false;
        }
    }

    public function addPaymentAttempt(array $paymentData): int|false
    {
        if (!$this->db->table('ajobmart_order_payments')->insert($paymentData)) {
            return false;
        }

        return (int) $this->db->insertID();
    }

    public function updatePaymentAttempt(
        int $paymentAttemptId,
        array $data
    ): bool {

        return $this->db->table('ajobmart_order_payments')
            ->where('id', $paymentAttemptId)
            ->update($data);
    }

    public function updateOrder(int $orderId, array $data): bool
    {
        return $this->update($orderId, $data);
    }

    public function getCustomerOrder(
        int $orderId,
        int $customerId
    ): ?array {

        return $this->where('id', $orderId)
            ->where('user_id', $customerId)
            ->first();
    }

    public function getOrderItems(int $orderId): array
    {
        return $this->db->table('ajobmart_order_items')
            ->where('order_id', $orderId)
            ->get()
            ->getResultArray();
    }

    public function getOrderShipments(int $orderId): array
    {
        return $this->db->table('ajobmart_order_shipments')
            ->where('order_id', $orderId)
            ->get()
            ->getResultArray();
    }
}
