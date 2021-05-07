<?php

declare(strict_types=1);

namespace App\Tests\Application\Validator;

use App\Application\Validator\UpdateBasketRequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AbstractRequestValidatorTest extends TestCase
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

    /** @dataProvider emptyBodyDataProvider */
    public function testEmptyBody(array $emptyBody)
    {
        $this->requestMock->expects($this->once())
            ->method('toArray')
            ->willReturn($emptyBody);

        $this->expectException(\InvalidArgumentException::class);

        $this->validator->validate($this->requestMock);
    }

    /** @return mixed */
    public function emptyBodyDataProvider(): array
    {
        return [
            [[]],
            [[null]],
        ];
    }
}
