<?php

namespace Tests\Unit\Services;

use App\Domain\Models\Product;
use App\Domain\Repositories\Catalog;
use App\Domain\Rules\FeeRules;
use App\Services\BasketService;
use App\Services\DiscountService;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class BasketServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testItAddsProductAndCalculatesTotal()
    {
        // fake product
        $product = new Product('R01', 'Red Widget', 32.95);

        // Mock Catalog
        $catalogMock = $this->createMock(Catalog::class);
        $catalogMock->method('getByProductCode')
            ->with('R01')
            ->willReturn($product);

        // Mock DiscountService (no discounts)
        $discountServiceMock = $this->createMock(DiscountService::class);
        $discountServiceMock->method('applyDiscounts')
            ->willReturnCallback(function ($items) {
                return $items; // no changes
            });

        // Mock FeeRules (no fee)
        $feeRuleMock = $this->createMock(FeeRules::class);
        $feeRuleMock->method('calculateFee')
            ->with(32.95)
            ->willReturn(0.0);

        $basketService = new BasketService(
            $catalogMock,
            $discountServiceMock,
            $feeRuleMock
        );

        // Act
        $basketService->addProduct('R01');
        $total = $basketService->getTotalPrice();

        // Assert
        $this->assertEquals(32.95, $total);
    }

    /**
     * @throws Exception
     */
    public function testItAppliesDiscountAndDeliveryFee()
    {
        $product = new Product('R01', 'Red Widget', 50.00);

        // Mock Catalog
        $catalogMock = $this->createMock(Catalog::class);
        $catalogMock->method('getByProductCode')
            ->willReturn($product);

        // Mock DiscountService to apply 20% discount
        $discountServiceMock = $this->createMock(DiscountService::class);
        $discountServiceMock->method('applyDiscounts')
            ->willReturnCallback(function ($items) {
                $item = $items[0];
                $item->setDiscount(20.0);
                $item->setFinalPrice(40.00); // 20% off
                return [$item];
            });

        // Mock FeeRules to apply a fixed fee
        $feeRuleMock = $this->createMock(FeeRules::class);
        $feeRuleMock->method('calculateFee')
            ->with(40.00)
            ->willReturn(4.95);

        $basketService = new BasketService(
            $catalogMock,
            $discountServiceMock,
            $feeRuleMock
        );

        // Act
        $basketService->addProduct('R01');
        $total = $basketService->getTotalPrice();

        // Assert
        $this->assertEquals(44.95, $total);
    }

    /**
     * @throws Exception
     */
    public function testThrowsExceptionIfProductDoesNotExist()
    {
        $catalogMock = $this->createMock(Catalog::class);
        $catalogMock->method('getByProductCode')->willReturn(null);

        $discountServiceMock = $this->createMock(DiscountService::class);
        $feeRuleMock = $this->createMock(FeeRules::class);

        $basketService = new BasketService($catalogMock, $discountServiceMock, $feeRuleMock);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Product code 'NON_EXISTENT_CODE' does not exist in the catalog.");

        $basketService->addProduct('NON_EXISTENT_CODE');
    }

}
