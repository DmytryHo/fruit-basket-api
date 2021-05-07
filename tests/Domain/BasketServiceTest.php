<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Application\DTO\BasketDTO;
use App\Application\DTO\BasketUpdateDTO;
use App\Domain\BasketService;
use App\Domain\BasketStorageInterface;
use App\Domain\Validator\BasketMaxCapacityValidator;
use PHPUnit\Framework\TestCase;

class BasketServiceTest extends TestCase
{
    protected BasketService $basketItemService;

    protected BasketStorageInterface $basketStorageMock;
    protected BasketMaxCapacityValidator $basketMaxCapacityValidatorMock;

    protected function setUp(): void
    {
        $this->basketStorageMock = $this->createMock(BasketStorageInterface::class);
        $this->basketMaxCapacityValidatorMock = $this->createMock(BasketMaxCapacityValidator::class);

        $this->basketItemService = new BasketService(
            $this->basketStorageMock,
            $this->basketMaxCapacityValidatorMock,
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

    public function testCreateNewBasketValidData()
    {
        $this->basketStorageMock->expects($this->once())
            ->method('createBasket');

        $this->basketItemService->createNewBasket(new BasketDTO('', 0));
    }

    public function testCreateNewBasketInvalidData()
    {
        $this->basketMaxCapacityValidatorMock->expects($this->once())
            ->method('validate')
            ->willThrowException(new \InvalidArgumentException());

        $this->expectException(\InvalidArgumentException::class);

        $this->basketStorageMock->expects($this->never())
            ->method('createBasket');

        $this->basketItemService->createNewBasket(new BasketDTO('', 0));
    }

    public function testUpdateNewBasketValidData()
    {
        $this->basketStorageMock->expects($this->once())
            ->method('updateBasketById');

        $this->basketItemService->updateBasketById(0, new BasketUpdateDTO(null, null));
    }

    public function testUpdateNewBasketInvalidData()
    {
        $this->basketMaxCapacityValidatorMock->expects($this->once())
            ->method('validate')
            ->willThrowException(new \InvalidArgumentException());

        $this->expectException(\InvalidArgumentException::class);

        $this->basketStorageMock->expects($this->never())
            ->method('updateBasketById');

        $this->basketItemService->updateBasketById(0, new BasketUpdateDTO(null, null));
    }
}
