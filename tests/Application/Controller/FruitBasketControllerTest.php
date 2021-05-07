<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Application\Controller\FruitBasketController;
use App\Application\Factory\DTOFactory;
use App\Domain\BasketService;
use App\Infrastructure\Persistence\Doctrine\Entity\Basket;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class FruitBasketControllerTest extends TestCase
{
    protected FruitBasketController $controller;
    protected BasketService $basketServiceMock;
    protected DTOFactory $DTOFactoryMock;

    protected const MEANINGLESS_BASKET_ID = 11;

    protected function setUp(): void
    {
        $this->basketServiceMock = $this->createMock(BasketService::class);
        $this->DTOFactoryMock = $this->createMock(DTOFactory::class);
        $this->controller = new FruitBasketController($this->basketServiceMock, $this->DTOFactoryMock);
    }

    protected function tearDown(): void
    {
        unset($this->basketServiceMock);
        unset($this->DTOFactoryMock);
        unset($this->controller);
    }

    public function testGetAllBasketsSuccessRequest(): void
    {
        $this->basketServiceMock->expects($this->once())
            ->method('getAllBaskets')
            ->willReturn([]);

        $response = $this->controller->getAllBaskets();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetBasketInfoNotFoundRequest(): void
    {
        $this->basketServiceMock->expects($this->once())
            ->method('getBasketById')
            ->willReturn(null);

        $response = $this->controller->getBasketInfo(self::MEANINGLESS_BASKET_ID);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testGetBasketInfoSuccessRequest(): void
    {
        $basketMock = $this->createMock(Basket::class);

        $this->basketServiceMock->expects($this->once())
            ->method('getBasketById')
            ->willReturn($basketMock);

        $response = $this->controller->getBasketInfo(self::MEANINGLESS_BASKET_ID);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testRemoveBasketNotFoundRequest(): void
    {
        $this->basketServiceMock->expects($this->once())
            ->method('deleteBasketById')
            ->willReturn(false);

        $response = $this->controller->removeBasket(self::MEANINGLESS_BASKET_ID);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testRemoveBasketSuccessRequest(): void
    {
        $this->basketServiceMock->expects($this->once())
            ->method('deleteBasketById')
            ->willReturn(true);

        $response = $this->controller->removeBasket(self::MEANINGLESS_BASKET_ID);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
