<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\BasketItemDTOInterface;
use App\Domain\BasketItemService;
use App\Domain\BasketStorageInterface;
use App\Domain\Validator\BasketFreeCapacityValidator;
use App\Domain\Validator\BasketItemTypeValidator;
use App\Domain\Validator\BasketItemWeightValidator;
use App\Infrastructure\Persistence\Doctrine\Entity\Basket;
use PHPUnit\Framework\TestCase;

class BasketItemServiceTest extends TestCase
{
    protected BasketItemService $basketItemService;

    protected BasketStorageInterface $basketStorageMock;
    protected BasketItemWeightValidator $basketItemWeightValidatorMock;
    protected BasketItemTypeValidator $basketItemTypeValidatorMock;
    protected BasketFreeCapacityValidator $basketFreeCapacityValidatorMock;

    protected function setUp(): void
    {
        $this->basketStorageMock = $this->createMock(BasketStorageInterface::class);
        $this->basketItemWeightValidatorMock = $this->createMock(BasketItemWeightValidator::class);
        $this->basketItemTypeValidatorMock = $this->createMock(BasketItemTypeValidator::class);
        $this->basketFreeCapacityValidatorMock = $this->createMock(BasketFreeCapacityValidator::class);

        $this->basketItemService = new BasketItemService(
            $this->basketStorageMock,
            $this->basketItemWeightValidatorMock,
            $this->basketItemTypeValidatorMock,
            $this->basketFreeCapacityValidatorMock
        );
    }

    protected function tearDown(): void
    {
        unset($this->basketStorage);
        unset($this->basketItemWeightValidator);
        unset($this->basketItemTypeValidator);
        unset($this->basketFreeCapacityValidator);
        unset($this->basketItemService);
    }

    public function testAddItemToBasketByIdValidData()
    {
        $this->basketStorageMock->expects($this->once())
            ->method('getBasketById')
            ->willReturn(new Basket('', 0));

        $this->basketStorageMock->expects($this->once())
            ->method('addItemsToBasketById');

        $basketItem = $this->createMock(BasketItemDTOInterface::class);

        $this->basketItemService->addItemToBasketById(1, [$basketItem]);
    }

    public function testAddItemToBasketByIdInValidBasket()
    {
        $this->basketStorageMock->expects($this->once())
            ->method('getBasketById')
            ->willReturn(null);

        $this->basketStorageMock->expects($this->never())
            ->method('addItemsToBasketById');

        $basketItem = $this->createMock(BasketItemDTOInterface::class);

        $this->basketItemService->addItemToBasketById(1, [$basketItem]);
    }

    public function testAddItemToBasketByIdInvalidTotalWeight()
    {
        $this->basketStorageMock->expects($this->once())
            ->method('getBasketById')
            ->willReturn(new Basket('', 0));

        $this->basketFreeCapacityValidatorMock->expects($this->once())
            ->method('validate')
            ->willThrowException(new \InvalidArgumentException());

        $this->expectException(\InvalidArgumentException::class);

        $this->basketStorageMock->expects($this->never())
            ->method('addItemsToBasketById');

        $basketItem = $this->createMock(BasketItemDTOInterface::class);
        $this->basketItemService->addItemToBasketById(1, [$basketItem]);
    }

    public function testAddItemToBasketByIdInvalidItemWeight()
    {
        $this->basketItemWeightValidatorMock->expects($this->once())
            ->method('validate')
            ->willThrowException(new \InvalidArgumentException());

        $this->expectException(\InvalidArgumentException::class);

        $this->basketStorageMock->expects($this->never())
            ->method('addItemsToBasketById');

        $basketItem = $this->createMock(BasketItemDTOInterface::class);
        $this->basketItemService->addItemToBasketById(1, [$basketItem]);
    }

    public function testAddItemToBasketByIdInvalidItemType()
    {
        $this->basketItemTypeValidatorMock->expects($this->once())
            ->method('validate')
            ->willThrowException(new \InvalidArgumentException());

        $this->expectException(\InvalidArgumentException::class);

        $this->basketStorageMock->expects($this->never())
            ->method('addItemsToBasketById');

        $basketItem = $this->createMock(BasketItemDTOInterface::class);
        $this->basketItemService->addItemToBasketById(1, [$basketItem]);
    }
}
