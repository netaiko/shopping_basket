<?php

namespace App\Domain\Rules;

use App\Domain\Models\BasketItem;

/**
 * The discount is applied to every second unit of the specified product code.
 * E.g. “buy one red widget, get the second half price”
 */
class BuyOneGetOneHalfPrice implements DiscountRule
{

    private string $applicableCode;

    private const DISCOUNT_PERCENT = 50.0;

    /**
     * @param string $productCode The product code eligible for the discount.
     */
    public function __construct(string $productCode)
    {
        $this->applicableCode = $productCode;
    }


    /**
     * Applies the discount rule to a list of products.
     *
     * Iterates though the provided products array, for every second product matching the applicable product code, the
     * product price is reduced by the DISCOUNT_MULTIPLIER
     *
     * @param array<BasketItem> $basketItems
     * @return array
     */
    public function apply(array $basketItems): array
    {
        $discountedItems = [];
        $count = 0;

        foreach ($basketItems as $basketItem) {
            if ($basketItem->getProductCode() === $this->applicableCode) {
                $count++;
                if ($count % 2 === 0) {
                    $price = $basketItem->getBasePrice() * self::DISCOUNT_PERCENT * 0.01;
                    $basketItem->setDiscount(self::DISCOUNT_PERCENT);
                    $basketItem->setFinalPrice($price);
                }
            }
            $discountedItems[] = $basketItem;

        }
        return $discountedItems;
    }
}
