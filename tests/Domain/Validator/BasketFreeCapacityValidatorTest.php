<?php

declare(strict_types=1);

namespace App\Tests\Domain\Validator;

use App\Application\DTO\BasketItemDTO;
use App\Domain\Validator\BasketFreeCapacityValidator;
use App\Infrastructure\Persistence\Doctrine\Entity\Basket;
use PHPUnit\Framework\TestCase;

class BasketFreeCapacityValidatorTest extends TestCase
{
    protected BasketFreeCapacityValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new BasketFreeCapacityValidator();
    }

    protected function tearDown(): void
    {
        unset($this->validator);
    }

    public function testValidateNotEnoughCapacity()
    {
        $basket = new Basket('', 12);
        $basketItem = new BasketItemDTO('', $basket->getFreeCapacity() + 1);

        $this->expectException(\RuntimeException::class);

        $this->validator->validate($basket, [$basketItem]);
    }

    public function testValidateEnoughCapacity()
    {
        $basket = new Basket('', 12);
        $basketItem = new BasketItemDTO('', $basket->getFreeCapacity());

        $this->validator->validate($basket, [$basketItem]);

        $this->expectNotToPerformAssertions();
    }
}
