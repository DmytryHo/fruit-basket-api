<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Application\Controller\FruitBasketController;
use App\Application\Factory\DTOFactory;
use App\Application\Validator\CreateBasketRequestValidator;
use App\Domain\BasketService;
use App\Infrastructure\Persistence\Doctrine\Entity\Basket;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FruitBasketControllerCreateTest extends TestCase
{
    protected FruitBasketController $controller;
    protected BasketService $basketServiceMock;
    protected DTOFactory $DTOFactoryMock;
    protected CreateBasketRequestValidator $createRequestValidatorMock;
    protected Basket $basketMock;

    protected const MEANINGLESS_BASKET_ID = 11;

    protected function setUp(): void
    {
        $this->basketServiceMock = $this->createMock(BasketService::class);
        $this->DTOFactoryMock = $this->createMock(DTOFactory::class);
        $this->createRequestValidatorMock = $this->createMock(CreateBasketRequestValidator::class);
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

    public function testCreateBasketBadRequest(): void
    {
        $requestMock = $this->createMock(Request::class);
        $this->createRequestValidatorMock->expects($this->once())
            ->method('validate')
            ->willThrowException(new \InvalidArgumentException());

        $response = $this->controller->createBasket($requestMock, $this->createRequestValidatorMock);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testCreateUnprocessableEntityRequest(): void
    {
        $requestMock = $this->createMock(Request::class);
        $this->createRequestValidatorMock->expects($this->once())
            ->method('validate');

        $this->basketServiceMock->expects($this->once())
            ->method('createNewBasket')
            ->willThrowException(new \Exception());

        $response = $this->controller->createBasket($requestMock, $this->createRequestValidatorMock);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateSuccessRequest(): void
    {
        $requestMock = $this->createMock(Request::class);
        $this->createRequestValidatorMock->expects($this->once())
            ->method('validate');

        $this->basketServiceMock->expects($this->once())
            ->method('createNewBasket')
            ->willReturn($this->basketMock);

        $this->basketMock->expects($this->once())
            ->method('getId')
            ->willReturn(self::MEANINGLESS_BASKET_ID);

        $response = $this->controller->createBasket($requestMock, $this->createRequestValidatorMock);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
