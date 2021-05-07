<?php

declare(strict_types=1);

namespace App\Tests\Domain\Validator;

use App\Application\DTO\BasketDTO;
use App\Domain\Validator\BasketMaxCapacityValidator;
use PHPUnit\Framework\TestCase;

class BasketMaxCapacityValidatorTest extends TestCase
{
    protected BasketMaxCapacityValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new BasketMaxCapacityValidator();
    }

    protected function tearDown(): void
    {
        unset($this->validator);
    }

    /** @dataProvider getInvalidMaxCapacityValues */
    public function testValidateInValidMaxCapacity($maxCapacityValue)
    {
        $basketItem = new BasketDTO('', $maxCapacityValue);

        $this->expectException(\InvalidArgumentException::class);

        $this->validator->validate($basketItem);
    }

    public function getInvalidMaxCapacityValues(): array
    {
        return [
            [0.9999],
            [9999.999],
        ];
    }

    /** @dataProvider getValidMaxCapacityValues */
    public function testValidateValidMaxCapacity($maxCapacityValue)
    {
        $basketItem = new BasketDTO('', $maxCapacityValue);
        $this->validator->validate($basketItem);
        $this->expectNotToPerformAssertions();
    }

    public function getValidMaxCapacityValues(): array
    {
        return [
            [0.999],
            [999.999],
        ];
    }
}
