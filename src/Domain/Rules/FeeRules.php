<?php

namespace App\Domain\Rules;

interface FeeRules
{
    public function calculateFee(float $basePrice): float;
}
