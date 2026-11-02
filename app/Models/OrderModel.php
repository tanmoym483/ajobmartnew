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
        'included_shipping',
        'total_amount',
        'payment_method',
        'payment_gateway',
        'payment_status',
        'order_status',
        'razorpay_order_id',
        'razorpay_payment_id',
    ];

    protected $useTimestamps = true;

    public function createOrderWithItems(
        array $orderData,
        array $cartItems,
        array $shipments = []
    ): int|false {
        $db = $this->db;
        $db->transBegin();

        try {
            if (!$this->insert($orderData)) {
                throw new \RuntimeException('Order insert failed.');
            }

            $orderId = (int) $this->getInsertID();

            if ($orderId <= 0) {
                throw new \RuntimeException('Invalid order ID.');
            }

            $itemBuilder = $db->table('ajobmart_order_items');

            foreach ($cartItems as $item) {
                $quantity = (int) ($item['quantity'] ?? 0);
                $price = (float) ($item['listing_price'] ?? 0);
                $allowance = (float) ($item['shipping_allowance'] ?? 0);

                if (
                    $quantity <= 0 ||
                    $price < 0 ||
                    $allowance < 0
                ) {
                    throw new \RuntimeException('Invalid order item.');
                }

                $itemBuilder->insert([
                    'order_id' => $orderId,
                    'product_id' => (int) $item['product_id'],
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
            }

            if (!empty($shipments)) {
                $shipmentBuilder = $db->table('ajobmart_order_shipments');

                foreach ($shipments as $shipment) {
                    $shipmentBuilder->insert([
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
                }
            }

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