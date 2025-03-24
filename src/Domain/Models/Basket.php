<?php

namespace App\Domain\Models;

/**
 * Represents a collection of items added in the shopping basket.
 * Holds multiple BasketItem objects and allows adding and retrieving them.
 */
class Basket
{
    /** @var array<BasketItem> List of items in the basket. */
    private array $items = [];

    /**
     * Adds new item to the basket.
     *
     * @param BasketItem $item
     * @return void
     */
    public function addItem(BasketItem $item): void
    {
        $this->items[] = $item;
    }

    /**
     * Retrieves all the items in the basket.
     *
     * @return array<BasketItem>
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
