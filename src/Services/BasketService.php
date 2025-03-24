<?php

namespace App\Services;

use App\Domain\Models\Basket;
use App\Domain\Models\BasketItem;
use App\Domain\Repositories\Catalog;
use App\Domain\Rules\FeeRules;
use InvalidArgumentException;

/**
 * BasketService is responsible for managing the basket's contents,
 * applying discounts, calculating the subtotal, and adding the delivery fee.
 */
class BasketService
{
    /** @var Basket The Basket model instance that stores the basket products. */
    private Basket $basket;

    /** @var Catalog The product catalog used to get the product details. */
    private Catalog $catalog;

    /** @var DiscountService Service that applies discount rules to the products added to the basket. */
    private DiscountService $discountService;

    /** @var FeeRules The fee rule that calculates the delivery fee based on the subtotal. */
    private FeeRules $deliveryFee;

    /**
     * @param Catalog $catalog The product catalog.
     * @param DiscountService $discountService Service to apply discounts.
     * @param FeeRules $deliveryFee Delivery fee calculation rule.
     */
    public function __construct(Catalog $catalog, DiscountService $discountService, FeeRules $deliveryFee)
    {
        $this->catalog = $catalog;
        $this->discountService = $discountService;
        $this->deliveryFee = $deliveryFee;
        $this->basket = new Basket();
    }

    /**
     * Adds a product to the basket by retrieving it from the catalog using its code.
     *
     * @param string $productCode The product code to add to the basket.
     * @return void
     */
    public function addProduct(string $productCode): void
    {
        $product = $this->catalog->getByProductCode($productCode);
        if ($product === null) {
            throw new InvalidArgumentException("Product code '{$productCode}' does not exist in the catalog.");
        }
        $basketItem = new BasketItem($product);
        $this->basket->addItem($basketItem);
    }

    /**
     * Calculates the total price of the basket by applying discount rules and adding the delivery fee.
     *
     * @return float The final total price.
     */
    public function getTotalPrice(): float
    {
        $basketItems = $this->discountService->applyDiscounts($this->basket->getItems());

        $subtotal = 0.0;
        foreach ($basketItems as $basketItem) {
            $subtotal += $basketItem->getFinalPrice();
        }

        $fee = $this->deliveryFee->calculateFee($subtotal);

        return round($subtotal + $fee, 2, PHP_ROUND_HALF_DOWN);
    }

}
