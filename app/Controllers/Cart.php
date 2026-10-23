<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\CartModel;
use CodeIgniter\HTTP\ResponseInterface;

class Cart extends BaseController
{
    protected CartModel $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
    }

    private function customerId(): ?int
    {
        if (!session()->get('ajobmart_customer_logged_in')) {
            return null;
        }

        $id = (int) session()->get('ajobmart_customer_db_id');

        return $id > 0 ? $id : null;
    }

    private function json(array $data, int $status = 200)
    {
        return $this->response
            ->setStatusCode($status)
            ->setJSON($data);
    }

    private function getPurchasableProduct(int $productId): ?array
    {
        $product = db_connect()
            ->table('ajobmart_products')
            ->where('id', $productId)
            // ->where('status', 'active')
            // ->where('approval_status', 'approved')
            ->get()
            ->getRowArray();

        return $product ?: null;
    }

    private function getCartPayload(int $customerId): array
    {
        $items = $this->cartModel->getCustomerCart($customerId);

        $validItems = [];
        $subtotal = 0.0;
        $count = 0;

        foreach ($items as $item) {
            $price = (float) $item['listing_price'];
            $quantity = (int) $item['quantity'];

            $item['line_total'] = round($price * $quantity, 2);

            $item['image_url'] = !empty($item['image_path'])
                ? site_url(
                    'shared-images/' .
                    ltrim($item['image_path'], '/\\')
                )
                : null;

            $item['purchasable'] =
                $item['status'] === 'active'
                && $item['approval_status'] === 'approved'
                && (int) $item['stock_quantity'] >= $quantity
                && $price >= 0;

            $validItems[] = $item;
            $subtotal += $item['line_total'];
            $count += $quantity;
        }

        return [
            'status' => true,
            'items' => $validItems,
            'count' => $count,
            'subtotal' => round($subtotal, 2),
            'delivery' => 0.00,
            'total' => round($subtotal, 2),
        ];
    }

    public function fetch()
    {
        $customerId = $this->customerId();

        if (!$customerId) {
            return $this->json([
                'status' => true,
                'logged_in' => false,
                'items' => [],
                'count' => 0,
                'subtotal' => 0,
                'delivery' => 0,
                'total' => 0,
            ]);
        }

        return $this->json(
            array_merge(
                ['logged_in' => true],
                $this->getCartPayload($customerId)
            )
        );
    }

    public function add()
    {
        $customerId = $this->customerId();

        if (!$customerId) {
            return $this->json([
                'status' => false,
                'code' => 'LOGIN_REQUIRED',
                'message' => 'Please log in to save your cart.',
            ], 401);
        }

        $productId = (int) $this->request->getPost('product_id');

        if ($productId <= 0) {
            return $this->json([
                'status' => false,
                'message' => 'Invalid product.',
            ], 422);
        }

        $db = $this->db;
        $db->transBegin();

        try {
            $product = $this->getPurchasableProduct($productId);

            if (!$product) {
                $db->transRollback();

                return $this->json([
                    'status' => false,
                    'message' => 'This product is not available.',
                ], 422);
            }

            $stock = (int) $product['stock_quantity'];
            $minimum = max(1, (int) $product['min_order_quantity']);

            if ($stock < $minimum) {
                $db->transRollback();

                return $this->json([
                    'status' => false,
                    'message' => 'Insufficient stock for the minimum order.',
                ], 422);
            }

            $existing = $db->table('ajobmart_cart_items')
                ->where('customer_id', $customerId)
                ->where('product_id', $productId)
                ->get()
                ->getRowArray();

            $newQuantity = $existing
                ? (int) $existing['quantity'] + $minimum
                : $minimum;

            if ($newQuantity > $stock) {
                $db->transRollback();

                return $this->json([
                    'status' => false,
                    'message' => 'Maximum available stock reached.',
                ], 422);
            }

            if ($existing) {
                $db->table('ajobmart_cart_items')
                    ->where('id', $existing['id'])
                    ->where('customer_id', $customerId)
                    ->update([
                        'quantity' => $newQuantity,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            } else {
                $db->table('ajobmart_cart_items')->insert([
                    'customer_id' => $customerId,
                    'product_id' => $productId,
                    'quantity' => $newQuantity,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Could not save cart item.');
            }

            $db->transCommit();

            return $this->json(array_merge(
                [
                    'status' => true,
                    'message' => 'Added to cart.',
                ],
                $this->getCartPayload($customerId)
            ));
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Cart add failed: ' . $e->getMessage());

            return $this->json([
                'status' => false,
                'message' => 'Unable to update your cart.',
            ], 500);
        }
    }

    public function update()
    {
        $customerId = $this->customerId();

        if (!$customerId) {
            return $this->json([
                'status' => false,
                'code' => 'LOGIN_REQUIRED',
            ], 401);
        }

        $productId = (int) $this->request->getPost('product_id');
        $quantity = filter_var(
            $this->request->getPost('quantity'),
            FILTER_VALIDATE_INT
        );

        if ($productId <= 0 || $quantity === false || $quantity < 0) {
            return $this->json([
                'status' => false,
                'message' => 'Invalid product or quantity.',
            ], 422);
        }

        $existing = $this->cartModel->findCustomerProduct(
            $customerId,
            $productId
        );

        if (!$existing) {
            return $this->json([
                'status' => false,
                'message' => 'Cart item not found.',
            ], 404);
        }

        if ($quantity === 0) {
            $this->cartModel->delete($existing['id']);

            return $this->json(array_merge(
                ['status' => true],
                $this->getCartPayload($customerId)
            ));
        }

        $product = $this->getPurchasableProduct($productId);

        if (!$product) {
            return $this->json([
                'status' => false,
                'message' => 'This product is not available.',
            ], 422);
        }

        $minimum = max(1, (int) $product['min_order_quantity']);
        $stock = (int) $product['stock_quantity'];

        if ($quantity < $minimum || $quantity > $stock) {
            return $this->json([
                'status' => false,
                'message' => 'Quantity must be between the minimum order and available stock.',
            ], 422);
        }

        $this->cartModel->update($existing['id'], [
            'quantity' => $quantity,
        ]);

        return $this->json(array_merge(
            ['status' => true],
            $this->getCartPayload($customerId)
        ));
    }

    public function remove()
    {
        $customerId = $this->customerId();

        if (!$customerId) {
            return $this->json([
                'status' => false,
                'code' => 'LOGIN_REQUIRED',
            ], 401);
        }

        $productId = (int) $this->request->getPost('product_id');

        if ($productId <= 0) {
            return $this->json([
                'status' => false,
                'message' => 'Invalid product.',
            ], 422);
        }

        $this->cartModel
            ->where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->delete();

        return $this->json(array_merge(
            ['status' => true],
            $this->getCartPayload($customerId)
        ));
    }
}
