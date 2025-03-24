<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Models\Product;
use App\Domain\Repositories\Catalog;

/**
 * A simple In-Memory implementation of the Catalog interface.
 * Useful for testing or non-persistent product data scenarios.
 */
class InMemoryCatalog implements Catalog
{
    /** @var array<Product> List of Products */
    private array $products = [];

    /**
     * Initialise the products with an array of product definitions.
     *
     * Example input:
     * [
     *      ['code' => 'R01', 'name' => 'Red Widget', 'price' => 32.95],
     * ]
     *
     * @param array $products An array defining product data
     */
    public function __construct(array $products)
    {
        foreach ($products as $product) {
            $this->addProduct($product['code'], $product['name'], $product['price']);
        }
    }

    /**
     * Add product to the catalog.
     *
     * @param string $code
     * @param string $name
     * @param float $price
     * @return void
     */
    public function addProduct(string $code, string $name, float $price): void
    {
        $this->products[$code] = new Product($code, $name, $price);
    }

    /**
     * Retrieves a product by its code.
     * Return a clone to prevent mutation.
     *
     * @param string $code
     * @return Product|null
     */
    public function getByProductCode(string $code): ?Product
    {
        if (isset($this->products[$code])) {
            return clone $this->products[$code];
        }
        return null;
    }
}
