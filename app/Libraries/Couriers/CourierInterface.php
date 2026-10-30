<?php

namespace App\Libraries\Couriers;

interface CourierInterface
{
    /**
     * Check serviceability and obtain a shipping quote.
     */
    public function quote(array $shipment): array;

    /**
     * Book a shipment after order confirmation.
     */
    public function createShipment(array $shipment): array;

    /**
     * Cancel an already booked shipment.
     */
    public function cancelShipment(string $awb): array;

    /**
     * Get tracking information.
     */
    public function track(string $awb): array;
}
