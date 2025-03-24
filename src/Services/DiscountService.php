<?php

namespace App\Services;

use App\Domain\Models\BasketItem;
use App\Domain\Rules\DiscountRule;

class DiscountService
{
    /** @var array<DiscountRule> Rules to be applied to the basket items */
    private array $rules = [];

    /**
     * Add a discount rule to the collection of rules.
     *
     * @param DiscountRule $rule
     * @return void
     */
    public function addRule(DiscountRule $rule): void
    {
        $this->rules[] = $rule;
    }

    /**
     * Applies all discount rules to a list of BasketItems.
     *
     * Iterates through all the discount rules in the $rules array and applies each rule to the provided items.
     *
     * @param array<BasketItem> $items Array of BasketItem objects to which the discounts will be applied.
     * @return array<BasketItem> The array of BasketItems after discounts have been applied.
     */
    public function applyDiscounts(array $items): array
    {
        foreach ($this->rules as $rule) {
            $items = $rule->apply($items);
        }
        return $items;
    }
}
