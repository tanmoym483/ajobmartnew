<?php

namespace App\Libraries\Couriers;

use CodeIgniter\HTTP\CURLRequest;

class ShadowfaxCourier implements CourierInterface
{
    private string $baseUrl;
    private string $apiToken;
    private string $accountType;

    private CURLRequest $client;

    public function __construct()
    {
        $environment = strtolower(
            (string) env('SHADOWFAX_ENV', 'staging')
        );

        $this->baseUrl = rtrim(
            (string) env(
                $environment === 'production'
                    ? 'SHADOWFAX_PRODUCTION_URL'
                    : 'SHADOWFAX_STAGING_URL',
                ''
            ),
            '/'
        );

        $this->apiToken = trim(
            (string) env('SHADOWFAX_API_TOKEN', '')
        );

        $this->accountType = strtolower(
            trim((string) env('SHADOWFAX_ACCOUNT_TYPE', 'regular'))
        );

        if (!in_array($this->accountType, ['regular', 'surface'], true)) {
            $this->accountType = 'regular';
        }

        $this->client = service('curlrequest', [
            'timeout' => 20,
            'connect_timeout' => 10,
        ]);
    }

    /**
     * Check one PIN for one Shadowfax shipment leg.
     */
    private function checkPincode(
        string $pincode,
        string $serviceType,
        string $expectedTier
    ): array {
        if (!$this->apiToken || !$this->baseUrl) {
            return [
                'success' => false,
                'serviceable' => false,
                'message' => 'Shadowfax credentials are not configured.',
            ];
        }

        try {
            $response = $this->client->get(
                $this->baseUrl . '/v1/clients/serviceability/',
                [
                    'headers' => [
                        'Authorization' => 'Token ' . $this->apiToken,
                        'Accept' => 'application/json',
                    ],
                    'query' => [
                        'service' => $serviceType,
                        'pincodes' => $pincode,
                    ],
                    'http_errors' => false,
                ]
            );

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if ($statusCode !== 200 || !is_array($data)) {
                log_message(
                    'error',
                    'Shadowfax serviceability API error. '
                    . 'Service: ' . $serviceType
                    . ', PIN: ' . $pincode
                    . ', HTTP: ' . $statusCode
                    . ', Span ID: ' . $response->getHeaderLine('x_span_id')
                    . ', Response: ' . $body
                );

                return [
                    'success' => false,
                    'serviceable' => false,
                    'service_type' => $serviceType,
                    'pincode' => $pincode,
                    'message' => 'Shadowfax serviceability request failed.',
                ];
            }

            $matched = false;
            $availableServices = [];

            foreach ($data as $item) {
                if (
                    isset($item['code']) &&
                    (string) $item['code'] === $pincode
                ) {
                    $availableServices = $item['services'] ?? [];

                    if (
                        is_array($availableServices) &&
                        in_array($expectedTier, $availableServices, true)
                    ) {
                        $matched = true;
                    }

                    break;
                }
            }

            return [
                'success' => true,
                'serviceable' => $matched,
                'http_status' => $statusCode,
                'service_type' => $serviceType,
                'pincode' => $pincode,
                'required_tier' => $expectedTier,
                'available_services' => $availableServices,
                'raw_response' => $data,
                'message' => $matched
                    ? 'PIN is serviceable.'
                    : 'Required Shadowfax service is unavailable for this PIN.',
            ];

        } catch (\Throwable $e) {
            log_message(
                'error',
                'Shadowfax API exception: ' . $e->getMessage()
            );

            return [
                'success' => false,
                'serviceable' => false,
                'service_type' => $serviceType,
                'pincode' => $pincode,
                'message' => 'Unable to connect to Shadowfax.',
            ];
        }
    }

    /**
     * Check all three legs of a marketplace shipment.
     */
    public function checkServiceability(array $shipment): array
    {
        $origin = trim(
            (string) ($shipment['origin_pincode'] ?? '')
        );

        $destination = trim(
            (string) ($shipment['destination_pincode'] ?? '')
        );

        if (
            !preg_match('/^[1-9][0-9]{5}$/', $origin) ||
            !preg_match('/^[1-9][0-9]{5}$/', $destination)
        ) {
            return [
                'success' => false,
                'serviceable' => false,
                'message' => 'Invalid shipment PIN codes.',
            ];
        }

        /*
         * Shadowfax service tiers depend on account type.
         */
        if ($this->accountType === 'surface') {
            $pickupTier = 'Surface_Mkt';
            $sellerDeliveryTier = 'Surface_RTS';
            $customerDeliveryTier = 'Surface';
        } else {
            $pickupTier = 'Marketplace';
            $sellerDeliveryTier = 'RTS';
            $customerDeliveryTier = 'Regular';
        }

        /*
         * LEG 1: Seller pickup.
         */
        $pickup = $this->checkPincode(
            $origin,
            'seller_pickup',
            $pickupTier
        );

        /*
         * LEG 2: Seller return/delivery.
         * The seller PIN is used as the return destination.
         */
        $sellerDelivery = $this->checkPincode(
            $origin,
            'seller_delivery',
            $sellerDeliveryTier
        );

        /*
         * LEG 3: Customer delivery.
         */
        $customerDelivery = $this->checkPincode(
            $destination,
            'customer_delivery',
            $customerDeliveryTier
        );

        $allRequestsSuccessful =
            !empty($pickup['success']) &&
            !empty($sellerDelivery['success']) &&
            !empty($customerDelivery['success']);

        $allLegsServiceable =
            !empty($pickup['serviceable']) &&
            !empty($sellerDelivery['serviceable']) &&
            !empty($customerDelivery['serviceable']);

        return [
            'success' => $allRequestsSuccessful,
            'serviceable' => (
                $allRequestsSuccessful &&
                $allLegsServiceable
            ),

            'courier' => 'shadowfax',
            'account_type' => $this->accountType,

            'pickup_pincode' => $origin,
            'delivery_pincode' => $destination,

            'pickup' => $pickup,
            'seller_delivery' => $sellerDelivery,
            'customer_delivery' => $customerDelivery,

            'message' => !$allRequestsSuccessful
                ? 'One or more Shadowfax API requests failed.'
                : (
                    $allLegsServiceable
                        ? 'All three Shadowfax shipment legs are serviceable.'
                        : 'One or more Shadowfax shipment legs are not serviceable.'
                ),
        ];
    }

    /**
     * Pricing will be connected after receiving
     * the Shadowfax rate card or quotation API.
     */
    public function quote(array $shipment): array
    {
        return [
            'success' => false,
            'serviceable' => false,
            'courier' => 'shadowfax',
            'message' => 'Shadowfax pricing is not configured.',
        ];
    }

    public function createShipment(array $shipment): array
    {
        return [
            'success' => false,
            'message' => 'Shadowfax shipment booking is not configured.',
        ];
    }

    public function cancelShipment(string $awb): array
    {
        return [
            'success' => false,
            'message' => 'Shadowfax cancellation is not configured.',
        ];
    }

    public function track(string $awb): array
    {
        return [
            'success' => false,
            'message' => 'Shadowfax tracking is not configured.',
        ];
    }
}
