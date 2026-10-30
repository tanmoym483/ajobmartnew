<?php

namespace App\Libraries\Couriers;

class CourierManager
{
    private array $couriers = [];

    public function register(
        string $name,
        CourierInterface $courier
    ): void {
        $this->couriers[$name] = $courier;
    }

    public function getQuotes(array $shipment): array
    {
        $quotes = [];

        foreach ($this->couriers as $name => $courier) {
            try {
                $result = $courier->quote($shipment);

                if (
                    !is_array($result) ||
                    empty($result['success']) ||
                    empty($result['serviceable']) ||
                    !isset($result['shipping']) ||
                    !is_numeric($result['shipping']) ||
                    !isset($result['customer_cost']) ||
                    !is_numeric($result['customer_cost'])
                ) {
                    continue;
                }

                $shipping = (float) $result['shipping'];
                $customerCost = (float) $result['customer_cost'];

                if (
                    !is_finite($shipping) ||
                    !is_finite($customerCost) ||
                    $shipping < 0 ||
                    $customerCost < 0 ||
                    $customerCost > $shipping
                ) {
                    continue;
                }

                $result['courier'] = $name;
                $result['shipping'] = round($shipping, 2);
                $result['customer_cost'] = round($customerCost, 2);

                $quotes[] = $result;

            } catch (\Throwable $e) {
                log_message(
                    'error',
                    "Courier quote failed [{$name}]: " . $e->getMessage()
                );
            }
        }

        return $quotes;
    }

    public function selectAutomatic(array $quotes): ?array
    {
        if (empty($quotes)) {
            return null;
        }

        usort($quotes, function ($a, $b) {
            return (float) $a['customer_cost']
                <=> (float) $b['customer_cost'];
        });

        return $quotes[0];
    }

    public function getCourier(string $name): ?CourierInterface
    {
        return $this->couriers[$name] ?? null;
    }
}
