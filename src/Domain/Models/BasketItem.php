<?php

namespace App\Domain\Models;

/**
 * Represents an individual item added to the basket.
 * Wraps product data and pricing, discounts, etc...
 */
class BasketItem
{
    /** @var string Product identifier (R01, B01 ...) */
    private string $productCode;

    /** @var string product name (Red Widget) */
    private string $name;

    /** @var float Original price of the product */
    private float $basePrice;

    /** @var float Final price after applying discount */
    private float $finalPrice;

    /** @var float Percentage discount applied to this item */
    private float $discount;

    /**
     * Initialises a basket item using the product information.
     * At creating, base and final prices are the same, and no discount is applied.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->productCode = $product->getCode();
        $this->name = $product->getName();
        $this->basePrice = $product->getPrice();
        $this->finalPrice = $product->getPrice();
        $this->discount = 0.0;
    }

    public function getBasePrice(): float
    {
        return $this->basePrice;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFinalPrice(): float
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(float $finalPrice): void
    {
        $this->finalPrice = $finalPrice;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }
}
