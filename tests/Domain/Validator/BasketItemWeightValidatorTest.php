<?php

declare(strict_types=1);

namespace App\Tests\Domain\Validator;

use App\Application\DTO\BasketItemDTO;
use App\Domain\Validator\BasketItemWeightValidator;
use PHPUnit\Framework\TestCase;

class BasketItemWeightValidatorTest extends TestCase
{
    protected BasketItemWeightValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new BasketItemWeightValidator();
    }

    protected function tearDown(): void
    {
        unset($this->validator);
    }

    /** @dataProvider getInvalidWeightValues */
    public function testValidateInValidWeight($weightValue)
    {
        $basketItem = new BasketItemDTO('', $weightValue);

        $this->expectException(\InvalidArgumentException::class);

        $this->validator->validate($basketItem);
    }

    public function getInvalidWeightValues(): array
    {
        return [
            [0.9999],
            [999.999],
        ];
    }

    /** @dataProvider getValidWeightValues */
    public function testValidateValidWeight($weightValue)
    {
        $basketItem = new BasketItemDTO('', $weightValue);
        $this->validator->validate($basketItem);
        $this->expectNotToPerformAssertions();
    }

    public function getValidWeightValues(): array
    {
        return [
            [0.999],
            [99.999],
        ];
    }
}
