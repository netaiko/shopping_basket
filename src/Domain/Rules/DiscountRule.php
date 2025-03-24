<?php

namespace App\Domain\Rules;

interface DiscountRule
{
    public function apply(array $basketItems): array;
}
