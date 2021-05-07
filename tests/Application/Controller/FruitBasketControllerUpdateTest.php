<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Application\Controller\FruitBasketController;
use App\Application\Factory\DTOFactory;
use App\Application\Validator\UpdateBasketRequestValidator;
use App\Domain\BasketService;
use App\Infrastructure\Persistence\Doctrine\Entity\Basket;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FruitBasketControllerUpdateTest extends TestCase
{
    protected FruitBasketController $controller;
    protected BasketService $basketServiceMock;
    protected DTOFactory $DTOFactoryMock;
    protected UpdateBasketRequestValidator $validator;
    protected Basket $basketMock;

    protected const MEANINGLESS_BASKET_ID = 11;

    protected function setUp(): void
    {
        $this->basketServiceMock = $this->createMock(BasketService::class);
        $this->DTOFactoryMock = $this->createMock(DTOFactory::class);
        $this->validator = $this->createMock(UpdateBasketRequestValidator::class);
        $this->basketMock = $this->createMock(Basket::class);
        $this->controller = $this->getMockBuilder(FruitBasketController::class)
            ->onlyMethods(['generateUrl'])
            ->setConstructorArgs([$this->basketServiceMock, $this->DTOFactoryMock])
            ->getMock();
    }

    protected function tearDown(): void
    {
        unset($this->basketServiceMock);
        unset($this->createRequestValidator);
        unset($this->DTOFactoryMock);
        unset($this->controller);
    }

    public function testUpdateBasketBadRequest(): void
    {
        $requestMock = $this->createMock(Request::class);
        $this->validator->expects($this->once())
            ->method('validate')
            ->willThrowException(new \InvalidArgumentException());

        $response = $this->controller->updateBasketInfo(
            self::MEANINGLESS_BASKET_ID,
            $requestMock,
            $this->validator
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUpdateNotFoundRequest(): void
    {
        $requestMock = $this->createMock(Request::class);
        $this->validator->expects($this->once())
            ->method('validate');

        $this->basketServiceMock->expects($this->once())
            ->method('updateBasketById')
            ->willReturn(null);

        $response = $this->controller->updateBasketInfo(
            self::MEANINGLESS_BASKET_ID,
            $requestMock,
            $this->validator
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testUpdateUnprocessableEntityRequest(): void
    {
        $requestMock = $this->createMock(Request::class);
        $this->validator->expects($this->once())
            ->method('validate');

        $this->basketServiceMock->expects($this->once())
            ->method('updateBasketById')
            ->willThrowException(new \Exception());

        $response = $this->controller->updateBasketInfo(
            self::MEANINGLESS_BASKET_ID,
            $requestMock,
            $this->validator
        );

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateSuccessRequest(): void
    {
        $requestMock = $this->createMock(Request::class);
        $this->validator->expects($this->once())
            ->method('validate');

        $this->basketServiceMock->expects($this->once())
            ->method('updateBasketById')
            ->willReturn($this->basketMock);

        $response = $this->controller->updateBasketInfo(
            self::MEANINGLESS_BASKET_ID,
            $requestMock,
            $this->validator
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
