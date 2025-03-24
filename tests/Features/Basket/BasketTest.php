<?php

namespace Tests\Features\Basket;

use App\Domain\Rules\BuyOneGetOneHalfPrice;
use App\Domain\Rules\DeliveryFeeRuleDefault;
use App\Infrastructure\Repositories\InMemoryCatalog;
use App\Services\BasketService;
use App\Services\DiscountService;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    private array $products;
    private array $deliveryFees;

    protected function setUp(): void
    {
        parent::setUp();
        $this->products = json_decode(file_get_contents(__DIR__ . '/../../../config/catalog.json'), true)['products'];
        $this->deliveryFees = json_decode(file_get_contents(__DIR__ . '/../../../config/delivery_costs.json'), true)['costs'];
    }

    private function createBasket(): BasketService
    {
        $catalog = new InMemoryCatalog($this->products);

        $discountService = new DiscountService();
        $discountService->addRule(new BuyOneGetOneHalfPrice("R01"));

        $deliveryFee = new DeliveryFeeRuleDefault($this->deliveryFees);

        return new BasketService($catalog, $discountService, $deliveryFee);
    }

    public function testTotalPriceNoDiscountHighDeliveryFee(): void
    {
        $basket = $this->createBasket();

        $basket->addProduct('B01');
        $basket->addProduct('G01');

        $price = $basket->getTotalPrice();

        $this->assertEquals(37.85, $price);
    }

    public function testTotalPriceThreeRedWidgetsWithBuyOneGetOneHalfPriceDiscount(): void
    {
        $basket = $this->createBasket();

        $basket->addProduct('R01');
        $basket->addProduct('R01');

        $price = $basket->getTotalPrice();

        $this->assertEquals(54.37, $price);
    }

    public function testTotalPriceNoDiscountLowDeliveryFee(): void
    {
        $basket = $this->createBasket();

        $basket->addProduct('R01');
        $basket->addProduct('G01');

        $price = $basket->getTotalPrice();

        $this->assertEquals(60.85, $price);
    }

    public function testTotalPriceOneSpecialDiscountWithFreeDelivery(): void
    {
        $basket = $this->createBasket();

        $basket->addProduct('B01');
        $basket->addProduct('B01');
        $basket->addProduct('R01');
        $basket->addProduct('R01');
        $basket->addProduct('R01');

        $price = $basket->getTotalPrice();

        $this->assertEquals(98.27, $price);
    }

}