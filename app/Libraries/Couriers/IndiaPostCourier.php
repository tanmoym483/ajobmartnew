<?php

namespace App\Libraries\Couriers;

use App\Models\ShippingModel;

class IndiaPostCourier implements CourierInterface
{
    private ShippingModel $shippingModel;

    public function __construct()
    {
        $this->shippingModel = new ShippingModel();
    }

    /**
     * Get an India Post quote using the existing rate model.
     *
     * Required shipment fields:
     * origin_pincode, destination_pincode, weight_grams
     *
     * Optional:
     * shipping_allowance
     */
    public function quote(array $shipment): array
    {
        $originPin = trim(
            (string) ($shipment['origin_pincode'] ?? '')
        );

        $destinationPin = trim(
            (string) ($shipment['destination_pincode'] ?? '')
        );

        $weightGrams = (int) ($shipment['weight_grams'] ?? 0);

        if (
            !preg_match('/^\d{6}$/', $originPin) ||
            !preg_match('/^\d{6}$/', $destinationPin) ||
            $weightGrams <= 0
        ) {
            return [
                'success' => false,
                'serviceable' => false,
                'courier' => 'india_post',
                'message' => 'Invalid PIN code or shipment weight.',
            ];
        }

        $zone = $this->shippingModel->resolveZone(
            $originPin,
            $destinationPin
        );

        if (!$zone) {
            return [
                'success' => false,
                'serviceable' => false,
                'courier' => 'india_post',
                'message' => 'India Post shipping zone could not be determined.',
            ];
        }

        $rate = $this->shippingModel->calculateRate(
            $zone,
            $weightGrams
        );

        if (!$rate) {
            return [
                'success' => false,
                'serviceable' => false,
                'courier' => 'india_post',
                'message' => 'No India Post rate is configured for this shipment.',
            ];
        }

        $shipping = (float) $rate['shipping'];

        // Allowance is optional. Existing cart logic can continue
        // applying it separately if it already does so.
        $allowance = max(
            0,
            (float) ($shipment['shipping_allowance'] ?? 0)
        );

        return [
            'success' => true,
            'serviceable' => true,
            'courier' => 'india_post',
            'courier_name' => $rate['courier_name'],
            'service_type' => $rate['service_type'],
            'zone_code' => $rate['zone_code'],
            'weight_grams' => $weightGrams,
            'base_charge' => $rate['base_charge'],
            'gst_amount' => $rate['gst_amount'],
            'shipping' => $shipping,
            'shipping_allowance' => $allowance,
            'customer_cost' => round(
                max(0, $shipping - $allowance),
                2
            ),
            'message' => 'India Post estimate calculated.',
        ];
    }

    /**
     * India Post booking is not implemented in the existing system.
     */
    public function createShipment(array $shipment): array
    {
        return [
            'success' => false,
            'message' => 'India Post shipment booking is not configured yet.',
        ];
    }

    public function cancelShipment(string $awb): array
    {
        return [
            'success' => false,
            'message' => 'India Post cancellation is not configured yet.',
        ];
    }

    public function track(string $awb): array
    {
        return [
            'success' => false,
            'message' => 'India Post tracking integration is not configured yet.',
        ];
    }
}
