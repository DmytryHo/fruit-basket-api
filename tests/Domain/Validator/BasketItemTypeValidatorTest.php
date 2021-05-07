<?php

declare(strict_types=1);

namespace App\Tests\Domain\Validator;

use App\Application\DTO\BasketItemDTO;
use App\Domain\Validator\BasketItemTypeValidator;
use App\Infrastructure\Persistence\Doctrine\Type\BasketItemTypeEnum;
use PHPUnit\Framework\TestCase;

class BasketItemTypeValidatorTest extends TestCase
{
    protected BasketItemTypeValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new BasketItemTypeValidator();
    }

    protected function tearDown(): void
    {
        unset($this->validator);
    }

    public function testValidateNotValidType()
    {
        $basketItem = new BasketItemDTO('', 1);

        $this->expectException(\InvalidArgumentException::class);

        $this->validator->validate($basketItem);
    }

    public function testValidateValidType()
    {
        $validTypes = BasketItemTypeEnum::VALUES;
        $basketItem = new BasketItemDTO(reset($validTypes), 1);

        $this->validator->validate($basketItem);

        $this->expectNotToPerformAssertions();
    }
}
