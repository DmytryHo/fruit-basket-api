<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Application\Controller\FruitBasketItemController;
use App\Application\Factory\DTOFactory;
use App\Application\Validator\AddItemToBasketRequestValidator;
use App\Domain\BasketItemService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FruitBasketItemControllerTest extends TestCase
{
    protected FruitBasketItemController $controller;
    protected BasketItemService $basketServiceMock;
    protected DTOFactory $DTOFactoryMock;
    protected AddItemToBasketRequestValidator $validator;

    protected const MEANINGLESS_BASKET_ID = 11;

    protected function setUp(): void
    {
        $this->basketServiceMock = $this->createMock(BasketItemService::class);
        $this->DTOFactoryMock = $this->createMock(DTOFactory::class);
        $this->validator = $this->createMock(AddItemToBasketRequestValidator::class);
        $this->controller = new FruitBasketItemController(
            $this->basketServiceMock,
            $this->DTOFactoryMock
        );
    }

    protected function tearDown(): void
    {
        unset($this->basketServiceMock);
        unset($this->DTOFactoryMock);
        unset($this->controller);
    }

    public function testRemoveItemsFromBasketNotFoundRequest()
    {
        $this->basketServiceMock->expects($this->once())
            ->method('cleanBasketById')
            ->willReturn(false);

        $response = $this->controller->removeItemsFromBasket(self::MEANINGLESS_BASKET_ID);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testAddItemsFromBasketSuccessRequest()
    {
        $this->basketServiceMock->expects($this->once())
            ->method('cleanBasketById')
            ->willReturn(true);

        $response = $this->controller->removeItemsFromBasket(self::MEANINGLESS_BASKET_ID);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    //REMOVE

    public function testAddItemsToBasketBadRequest(): void
    {
        $requestMock = $this->createMock(Request::class);
        $this->validator->expects($this->once())
            ->method('validate')
            ->willThrowException(new \InvalidArgumentException());

        $response = $this->controller->addItemsToBasket(
            self::MEANINGLESS_BASKET_ID,
            $requestMock,
            $this->validator
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testAddItemsNotFoundRequest(): void
    {
        $requestMock = $this->createMock(Request::class);
        $this->validator->expects($this->once())
            ->method('validate');

        $this->basketServiceMock->expects($this->once())
            ->method('addItemToBasketById')
            ->willReturn(false);

        $response = $this->controller->addItemsToBasket(
            self::MEANINGLESS_BASKET_ID,
            $requestMock,
            $this->validator
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testAddItemsToBasketUnprocessableEntityRequest(): void
    {
        $requestMock = $this->createMock(Request::class);
        $this->validator->expects($this->once())
            ->method('validate');

        $this->basketServiceMock->expects($this->once())
            ->method('addItemToBasketById')
            ->willThrowException(new \Exception());

        $response = $this->controller->addItemsToBasket(
            self::MEANINGLESS_BASKET_ID,
            $requestMock,
            $this->validator
        );

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testAddItemsToBasketSuccessRequest(): void
    {
        $requestMock = $this->createMock(Request::class);

        $this->validator->expects($this->once())
            ->method('validate');

        $this->basketServiceMock->expects($this->once())
            ->method('addItemToBasketById')
            ->willReturn(true);

        $response = $this->controller->addItemsToBasket(
            self::MEANINGLESS_BASKET_ID,
            $requestMock,
            $this->validator
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
