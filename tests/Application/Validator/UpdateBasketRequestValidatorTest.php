<?php

declare(strict_types=1);

namespace App\Tests\Application\Validator;

use App\Application\Validator\UpdateBasketRequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class UpdateBasketRequestValidatorTest extends TestCase
{
    protected UpdateBasketRequestValidator $validator;
    protected Request $requestMock;

    protected function setUp(): void
    {
        $this->validator = new UpdateBasketRequestValidator();
        $this->requestMock = $this->createMock(Request::class);
    }

    protected function tearDown(): void
    {
        unset($this->validator);
        unset($this->requestMock);
    }

    /** @dataProvider nonStringValuesDataProvider */
    public function testWrongNameFormat(mixed $nameValue)
    {
        $this->requestMock->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'name' => $nameValue,
            ]);

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
    public function testCorrectNameFormat(string $nameValue)
    {
        $this->requestMock->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'name' => $nameValue,
            ]);

        $this->validator->validate($this->requestMock);
    }

    /**
     * @return mixed
     * @dataProvider
     */
    public function stringValuesDataProvider(): array
    {
        return [
            ['Test basket name'],
            ['Test basket name'],
        ];
    }

    /** @dataProvider wrongCapacityValuesDataProvider */
    public function testWrongCapacityFormat(mixed $wrongCapacity)
    {
        $this->requestMock->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'maxCapacity' => $wrongCapacity,
            ]);

        $this->expectException(\InvalidArgumentException::class);

        $this->validator->validate($this->requestMock);
    }

    /**
     * @return mixed
     * @dataProvider
     */
    public function wrongCapacityValuesDataProvider(): array
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

    /** @dataProvider correctCapacityValuesDataProvider */
    public function testCorrectCapacityFormat(mixed $capacityValue)
    {
        $this->requestMock->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'maxCapacity' => $capacityValue,
            ]);

        $this->validator->validate($this->requestMock);
    }

    /** @return mixed */
    public function correctCapacityValuesDataProvider(): array
    {
        return [
            [99.99],
            [1],
            [20],
        ];
    }
}
