<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ShippingModel;

class Shipping extends BaseController
{
    protected $db;
    protected ShippingModel $shippingModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->shippingModel = new ShippingModel();
    }

    private function jsonResponse(array $data, int $status = 200)
    {
        return $this->response
            ->setStatusCode($status)
            ->setJSON($data);
    }

    private function getPayload(): array
    {
        return $this->request->getJSON(true)
            ?? $this->request->getPost()
            ?? [];
    }

    private function validPin(string $pin): bool
    {
        return (bool) preg_match('/^\d{6}$/', trim($pin));
    }

    /**
     * GET /shipping/customer-address
     *
     * Returns the logged-in customer's default address, if available.
     */
    public function customerAddress()
    {
        $customerId = (int) session()->get('ajobmart_customer_db_id');

        if ($customerId <= 0) {
            return $this->jsonResponse([
                'success' => true,
                'logged_in' => false,
                'address' => null,
            ]);
        }

        $address = $this->db->table('ajobmart_customer_addresses')
            ->where('customer_id', $customerId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        return $this->jsonResponse([
            'success' => true,
            'logged_in' => true,
            'address' => $address ?: null,
        ]);
    }


    public function customerAddresses()
{
    $customerId = (int) session()->get('ajobmart_customer_db_id');

    if ($customerId <= 0) {
        return $this->jsonResponse([
            'success'   => true,
            'logged_in' => false,
            'addresses' => [],
        ]);
    }

    $addresses = $this->db
        ->table('ajobmart_customer_addresses')
        ->where('customer_id', $customerId)
        ->orderBy('is_default', 'DESC')
        ->orderBy('id', 'DESC')
        ->get()
        ->getResultArray();

    return $this->jsonResponse([
        'success'   => true,
        'logged_in' => true,
        'addresses' => $addresses,
    ]);
}

    /**
     * POST /shipping/estimate
     *
     * JSON:
     * {
     *   "pincode": "700001",
     *   "items": [
     *     {"product_id": 1, "quantity": 2},
     *     {"product_id": 2, "quantity": 1}
     *   ]
     * }
     */
    public function estimate()
    {
        $payload = $this->getPayload();

        $destinationPin = trim((string) ($payload['pincode'] ?? ''));
        $items = $payload['items'] ?? [];

        if (!$this->validPin($destinationPin)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Enter a valid 6-digit PIN code.',
            ], 422);
        }

        if (!is_array($items) || empty($items)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Your cart is empty or cart items could not be read.',
            ], 422);
        }

        // Confirm that the destination PIN exists in your directory.
        $destinationExists = $this->db->table('ajobmart_pincode_directory')
            ->where('pincode', $destinationPin)
            ->where('is_active', 1)
            ->countAllResults();

        if (!$destinationExists) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'This PIN code is not yet available for shipping estimates.',
            ], 422);
        }

        $sellerShipments = [];

        foreach ($items as $item) {
            $productId = (int) ($item['product_id'] ?? $item['id'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? $item['qty'] ?? 0);

            if ($productId <= 0 || $quantity <= 0 || $quantity > 1000) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'One or more cart items have invalid quantities.',
                ], 422);
            }

            $product = $this->db->table('ajobmart_products')
                ->select(
                    'id, seller_id, product_name, shipping_weight, ' .
                    'status, approval_status'
                )
                ->where('id', $productId)
                // ->where('status', 'active')
                ->get()
                ->getRowArray();

            if (!$product) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'A product in your cart is no longer available.',
                ], 422);
            }

            /*
             * IMPORTANT:
             * If your existing purchase logic requires approval_status='approved',
             * apply that same rule here too. Do not create a different sale rule
             * for shipping estimates.
             */

            $sellerId = (int) $product['seller_id'];
            $weightKg = (float) $product['shipping_weight'];

            if ($sellerId <= 0 || $weightKg <= 0) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Shipping weight or seller information is missing for: '
                        . $product['product_name'],
                ], 422);
            }

            // Product shipping_weight is assumed to be in kilograms.
            $unitWeightGrams = (int) ceil($weightKg * 1000);
            $lineWeightGrams = $unitWeightGrams * $quantity;

            if (!isset($sellerShipments[$sellerId])) {
                $sellerShipments[$sellerId] = [
                    'seller_id' => $sellerId,
                    'weight_grams' => 0,
                    'items' => 0,
                ];
            }

            $sellerShipments[$sellerId]['weight_grams'] += $lineWeightGrams;
            $sellerShipments[$sellerId]['items'] += $quantity;
        }

        $sellerResults = [];
        $grandShipping = 0;

        foreach ($sellerShipments as $sellerId => $shipment) {
            $sellerAddress = $this->db->table('ajobmart_seller_addresses')
                ->where('seller_id', $sellerId)
                ->where('is_active', 1)
                ->orderBy('is_default', 'DESC')
                ->orderBy('id', 'DESC')
                ->get()
                ->getRowArray();

            if (!$sellerAddress || !$this->validPin((string) $sellerAddress['pincode'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Shipping pickup PIN code is missing for seller ID '
                        . $sellerId . '.',
                ], 422);
            }

            $weightGrams = (int) $shipment['weight_grams'];

            $zone = $this->shippingModel->resolveZone(
                (string) $sellerAddress['pincode'],
                $destinationPin
            );

            if (!$zone) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Shipping zone could not be determined for seller ID '
                        . $sellerId . '. Please contact support.',
                ], 422);
            }

            $rate = $this->shippingModel->calculateRate($zone, $weightGrams);

            if (!$rate) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'No shipping rate is configured for seller ID '
                        . $sellerId . ', zone ' . $zone . ', weight '
                        . $weightGrams . 'g.',
                ], 422);
            }

            $grandShipping += $rate['shipping'];

            $sellerResults[] = [
                'seller_id' => (int) $sellerId,
                'zone_code' => $zone,
                'weight_grams' => $weightGrams,
                'courier_name' => $rate['courier_name'],
                'shipping' => $rate['shipping'],
            ];
        }

        return $this->jsonResponse([
            'success' => true,
            'pincode' => $destinationPin,
            'seller_shipments' => $sellerResults,
            'total_shipping' => round($grandShipping, 2),
            'message' => 'Shipping estimate calculated.',
        ]);
    }

    /**
     * POST /shipping/save-address
     * For logged-in customers who need to save a delivery address.
     */
    public function saveAddress()
    {
        $customerId = (int) session()->get('ajobmart_customer_db_id');

        if ($customerId <= 0) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Please log in to save your address.',
            ], 401);
        }

        $payload = $this->getPayload();

        $name = trim((string) ($payload['recipient_name'] ?? ''));
        $mobile = preg_replace('/\D+/', '', (string) ($payload['mobile'] ?? ''));
        $pin = trim((string) ($payload['pincode'] ?? ''));
        $line1 = trim((string) ($payload['address_line1'] ?? ''));
        $line2 = trim((string) ($payload['address_line2'] ?? ''));
        $landmark = trim((string) ($payload['landmark'] ?? ''));
        $city = trim((string) ($payload['city'] ?? ''));
        $state = trim((string) ($payload['state'] ?? ''));
        $type = (string) ($payload['address_type'] ?? 'home');

        if (
            $name === '' ||
            !preg_match('/^\d{10}$/', $mobile) ||
            !$this->validPin($pin) ||
            $line1 === '' ||
            $city === '' ||
            $state === ''
        ) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Please complete the required address fields correctly.',
            ], 422);
        }

        if (!in_array($type, ['home', 'work', 'other'], true)) {
            $type = 'home';
        }

        $pinRow = $this->db->table('ajobmart_pincode_directory')
            ->where('pincode', $pin)
            ->where('is_active', 1)
            ->get()
            ->getRowArray();

        if (!$pinRow) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'This PIN code is not available for delivery estimates yet.',
            ], 422);
        }

        $this->db->transStart();

        // Keep one default address for this customer.
        $this->db->table('ajobmart_customer_addresses')
            ->where('customer_id', $customerId)
            ->update(['is_default' => 0]);

        $this->db->table('ajobmart_customer_addresses')->insert([
            'customer_id' => $customerId,
            'recipient_name' => $name,
            'mobile' => $mobile,
            'pincode' => $pin,
            'state' => $state,
            'city' => $city,
            'address_line1' => $line1,
            'address_line2' => $line2,
            'landmark' => $landmark,
            'address_type' => $type,
            'is_default' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Unable to save the address. Please try again.',
            ], 500);
        }

        return $this->jsonResponse([
            'success' => true,
            'pincode' => $pin,
            'message' => 'Address saved successfully.',
        ]);
    }
}