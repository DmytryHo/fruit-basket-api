<?php

declare(strict_types=1);

namespace App\Domain;

interface BasketStorageInterface
{
    public function createBasket(BasketDTOInterface $basketDTO): BasketInterface;

    /** @return BasketInterface[] */
    public function getAllBaskets(): array;

    public function getBasketById(int $basketId): ?BasketInterface;

    public function updateBasketById(int $basketId, BasketUpdateDTOInterface $basketDTO): ?BasketInterface;

    public function deleteBasketById(int $basketId): bool;

    public function cleanBasketById(int $basketId): bool;

    /** @param BasketItemDTOInterface[] $items */
    public function addItemsToBasketById(int $basketId, array $items): bool;
}
