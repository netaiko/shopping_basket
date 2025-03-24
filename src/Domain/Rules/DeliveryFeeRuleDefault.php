<?php

namespace App\Domain\Rules;

/**
 * DeliveryFeeRuleDefault
 *
 * Calculates delivery fees based on a set of threshold rules.
 * Example:
 * - Orders under $50 = $4.95
 * - Orders under $90 = $2.95
 * - Orders >= $90 = free delivery
 */
class DeliveryFeeRuleDefault implements FeeRules
{
    private array $shippingRates;


    /**
     * Accepts an array of delivery fee threshold and shorts them ensuring that the highest applicable threshold is
     * matched first.
     * @param $rates
     */
    public function __construct($rates)
    {
        usort($rates, function ($a, $b) {
            return $b['threshold'] <=> $a['threshold'];
        });
        $this->shippingRates = $rates;
    }

    /**
     * Calculates the delivery fee based on the provided base price.
     *
     * @param float $basePrice The total price of the basket before fees
     * @return float The delivery fee based on the matching threshold
     */
    public function calculateFee(float $basePrice): float
    {
        foreach ($this->shippingRates as $rate) {
            if ($basePrice >= $rate['threshold']) {
                return $rate['fee'];
            }
        }
        return 0.0;
    }
}
