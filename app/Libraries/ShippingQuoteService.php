<?php

namespace App\Libraries;

use App\Libraries\Couriers\CourierManager;
use App\Libraries\Couriers\IndiaPostCourier;
use App\Libraries\Couriers\ShadowfaxCourier;

class ShippingQuoteService
{
    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    private function validPin(string $pin): bool
    {
        return (bool) preg_match('/^[1-9][0-9]{5}$/', trim($pin));
    }

    /**
     * Calculate shipping separately for every seller.
     *
     * $cartItems must come from CartModel::getCustomerCart().
     */
    public function calculate(array $cartItems, string $destinationPin): array
    {
        $destinationPin = trim($destinationPin);

        if (!$this->validPin($destinationPin)) {
            return [
                'success' => false,
                'message' => 'Invalid delivery PIN code.',
            ];
        }

        if (empty($cartItems)) {
            return [
                'success' => false,
                'message' => 'Your cart is empty.',
            ];
        }

        $destinationExists = $this->db
            ->table('ajobmart_pincode_directory')
            ->where('pincode', $destinationPin)
            ->where('is_active', 1)
            ->countAllResults();

        if (!$destinationExists) {
            return [
                'success' => false,
                'message' => 'This PIN code is not available for shipping estimates.',
            ];
        }

        // Register only enabled quote providers.
        $courierManager = new CourierManager();

        $providers = $this->db
            ->table('ajobmart_courier_providers')
            ->where('is_enabled', 1)
            ->where('is_quote_enabled', 1)
            ->get()
            ->getResultArray();

        foreach ($providers as $provider) {
            switch ($provider['courier_code'] ?? '') {
                case 'india_post':
                    $courierManager->register(
                        'india_post',
                        new IndiaPostCourier()
                    );
                    break;

                case 'shadowfax':
                    $courierManager->register(
                        'shadowfax',
                        new ShadowfaxCourier()
                    );
                    break;
            }
        }

        $sellerShipments = [];

        foreach ($cartItems as $cartItem) {
            $productId = (int) ($cartItem['product_id'] ?? 0);
            $quantity = (int) ($cartItem['quantity'] ?? 0);

            if (
                $productId <= 0 ||
                $quantity <= 0 ||
                $quantity > 1000
            ) {
                return [
                    'success' => false,
                    'message' => 'One or more cart items have invalid quantities.',
                ];
            }

            // Read current shipping data from the database.
            $product = $this->db
                ->table('ajobmart_products')
                ->select(
                    'id, seller_id, product_name, shipping_weight, shipping_allowance'
                )
                ->where('id', $productId)
                ->get()
                ->getRowArray();

            if (!$product) {
                return [
                    'success' => false,
                    'message' => 'A product in your cart is no longer available.',
                ];
            }

            $sellerId = (int) $product['seller_id'];
            $weightKg = (float) ($product['shipping_weight'] ?? 0);
            $allowance = (float) ($product['shipping_allowance'] ?? 0);

            if (
                $sellerId <= 0 ||
                $weightKg <= 0 ||
                $allowance < 0
            ) {
                return [
                    'success' => false,
                    'message' => 'Shipping information is missing for '
                        . ($product['product_name'] ?? 'a product') . '.',
                ];
            }

            $unitWeightGrams = (int) ceil($weightKg * 1000);
            $lineWeightGrams = $unitWeightGrams * $quantity;

            if (!isset($sellerShipments[$sellerId])) {
                $sellerShipments[$sellerId] = [
                    'seller_id' => $sellerId,
                    'weight_grams' => 0,
                    'items' => 0,
                    'shipping_allowance' => 0,
                ];
            }

            $sellerShipments[$sellerId]['weight_grams'] += $lineWeightGrams;
            $sellerShipments[$sellerId]['items'] += $quantity;
            $sellerShipments[$sellerId]['shipping_allowance'] +=
                $allowance * $quantity;
        }

        $sellerResults = [];
        $totalActualShipping = 0.0;
        $totalCustomerShipping = 0.0;
        $totalAllowance = 0.0;

        foreach ($sellerShipments as $sellerId => $shipment) {
            $sellerAddress = $this->db
                ->table('ajobmart_seller_addresses')
                ->where('seller_id', $sellerId)
                ->where('is_active', 1)
                ->orderBy('is_default', 'DESC')
                ->orderBy('id', 'DESC')
                ->get()
                ->getRowArray();

            if (
                !$sellerAddress ||
                !$this->validPin((string) ($sellerAddress['pincode'] ?? ''))
            ) {
                return [
                    'success' => false,
                    'message' => 'Shipping pickup PIN code is missing for seller ID '
                        . $sellerId . '.',
                ];
            }

            $originPin = trim((string) $sellerAddress['pincode']);
            $weightGrams = (int) $shipment['weight_grams'];
            $allowance = round(
                (float) $shipment['shipping_allowance'],
                2
            );

            $courierShipment = [
                'seller_id' => (int) $sellerId,
                'origin_pincode' => $originPin,
                'destination_pincode' => $destinationPin,
                'weight_grams' => $weightGrams,
                'shipping_allowance' => $allowance,
            ];

            $quotes = $courierManager->getQuotes($courierShipment);
            $selectedQuote = $courierManager->selectAutomatic($quotes);

            if (!$selectedQuote) {
                return [
                    'success' => false,
                    'message' => 'No courier service is available for seller ID '
                        . $sellerId . '.',
                ];
            }

            $actualShipping = round(
                (float) $selectedQuote['shipping'],
                2
            );

            // CourierManager already deducted the allowance.
            $customerShipping = round(
                (float) $selectedQuote['customer_cost'],
                2
            );

            $courierCode = (string) $selectedQuote['courier'];

            $totalActualShipping += $actualShipping;
            $totalCustomerShipping += $customerShipping;
            $totalAllowance += $allowance;

            $sellerResults[] = [
                'seller_id' => (int) $sellerId,
                'origin_pincode' => $originPin,
                'destination_pincode' => $destinationPin,
                'weight_grams' => $weightGrams,
                'item_quantity' => (int) $shipment['items'],
                'courier_code' => $courierCode,
                'courier_name' => $selectedQuote['courier_name']
                    ?? ucwords(str_replace('_', ' ', $courierCode)),
                'zone_code' => $selectedQuote['zone_code'] ?? null,
                'actual_shipping' => $actualShipping,
                'shipping_allowance' => $allowance,
                'customer_shipping' => $customerShipping,
            ];
        }

        return [
            'success' => true,
            'pincode' => $destinationPin,
            'seller_shipments' => $sellerResults,
            'total_actual_shipping' => round($totalActualShipping, 2),
            'total_customer_shipping' => round($totalCustomerShipping, 2),
            'total_shipping_allowance' => round($totalAllowance, 2),
        ];
    }
}