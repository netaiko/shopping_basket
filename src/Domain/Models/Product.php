<?php

namespace App\Domain\Models;

/**
 * Represents a product in the catalogue.
 */
class Product
{

    /** @var string Unique identifier (R01, B01 ...) */
    private string $code;

    /** @var string Human-readable product name (Red Widget) */
    private string $name;

    /** @var float Base price of the product */
    private float $price;

    /**
     * Product contractor. Initialise the product with the main information.
     *
     * @param string $code
     * @param string $name
     * @param float $price
     */
    public function __construct(string $code, string $name, float $price)
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
