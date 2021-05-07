<?php

declare(strict_types=1);

namespace App\Tests\Application\Validator;

use App\Application\Validator\AddItemToBasketRequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AddItemToBasketRequestValidatorTest extends TestCase
{
    protected AddItemToBasketRequestValidator $validator;
    protected Request $requestMock;

    protected const WEIGHT_VALID_VALUE = 1;
    protected const TYPE_VALID_VALUE = 'Valid Type';

    protected function setUp(): void
    {
        $this->validator = new AddItemToBasketRequestValidator();
        $this->requestMock = $this->createMock(Request::class);
    }

    protected function tearDown(): void
    {
        unset($this->validator);
        unset($this->requestMock);
    }

    /** @dataProvider nonStringValuesDataProvider */
    public function testWrongTypeFormat(mixed $typeValue)
    {
        $this->requestMock->expects($this->once())
            ->method('toArray')
            ->willReturn([[
                'type' => $typeValue,
                'weight' => self::WEIGHT_VALID_VALUE,
            ]]);

        $this->expectException(\InvalidArgumentException::class);

        $this->validator->validate($this->requestMock);
    }

    /**
     * @return mixed
     * @dataProvider
     */
    public function nonStringValuesDataProvider(): array
    {
        return [
            [true],
            [[]],
            [-1],
            [0],
            [1.33],
            [json_decode('{}')],
        ];
    }

    /** @dataProvider stringValuesDataProvider */
    public function testCorrectTypeFormat(string $typeValue)
    {
        $this->requestMock->expects($this->once())
            ->method('toArray')
            ->willReturn([[
                'type' => $typeValue,
                'weight' => self::WEIGHT_VALID_VALUE,
            ]]);

        $this->validator->validate($this->requestMock);
    }

    public function stringValuesDataProvider(): array
    {
        return [
            ['Test basket name'],
            ['Test basket name'],
        ];
    }

    /** @dataProvider wrongWeightValuesDataProvider */
    public function testWrongWeightFormat(mixed $capacityValue)
    {
        $this->requestMock->expects($this->once())
            ->method('toArray')
            ->willReturn([[
                'type' => self::TYPE_VALID_VALUE,
                'weight' => $capacityValue,
            ]]);

        $this->expectException(\InvalidArgumentException::class);

        $this->validator->validate($this->requestMock);
    }

    /**
     * @return mixed
     * @dataProvider
     */
    public function wrongWeightValuesDataProvider(): array
    {
        return [
            [true],
            [[]],
            [-1],
            [0],
            ['string'],
            ['string'],
            [json_decode('{}')],
        ];
    }

    /** @dataProvider correctWeightValuesDataProvider */
    public function testCorrectWeightFormat(mixed $capacityValue)
    {
        $this->requestMock->expects($this->once())
            ->method('toArray')
            ->willReturn([[
                'type' => self::TYPE_VALID_VALUE,
                'weight' => $capacityValue,
            ]]);

        $this->validator->validate($this->requestMock);
    }

    /** @return mixed */
    public function correctWeightValuesDataProvider(): array
    {
        return [
            [99.99],
            [1],
            [20],
        ];
    }

    public function testWeightRequired()
    {
        $this->requestMock->expects($this->once())
            ->method('toArray')
            ->willReturn([[
                'type' => self::TYPE_VALID_VALUE,
            ]]);

        $this->expectException(\InvalidArgumentException::class);

        $this->validator->validate($this->requestMock);
    }

    public function testTypeRequired()
    {
        $this->requestMock->expects($this->once())
            ->method('toArray')
            ->willReturn([[
                'weight' => self::WEIGHT_VALID_VALUE,
            ]]);

        $this->expectException(\InvalidArgumentException::class);

        $this->validator->validate($this->requestMock);
    }
}
