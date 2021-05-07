<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Validator\BasketMaxCapacityValidator;

class BasketService
{
    public function __construct(
        protected BasketStorageInterface $basketStorage,
        protected BasketMaxCapacityValidator $basketMaxCapacityValidator
    ) {
    }

    public function createNewBasket(BasketDTOInterface $basketDTO): BasketInterface
    {
        $this->basketMaxCapacityValidator->validate($basketDTO);

        return $this->basketStorage->createBasket($basketDTO);
    }

    /** @return BasketInterface[] */
    public function getAllBaskets(): array
    {
        return $this->basketStorage->getAllBaskets();
    }

    public function getBasketById(int $basketId): ?BasketInterface
    {
        return $this->basketStorage->getBasketById($basketId);
    }

    public function updateBasketById(int $basketId, BasketUpdateDTOInterface $basketDto): ?BasketInterface
    {
        $this->basketMaxCapacityValidator->validate($basketDto);

        return $this->basketStorage->updateBasketById($basketId, $basketDto);
    }

    public function deleteBasketById(int $basketId): bool
    {
        return $this->basketStorage->deleteBasketById($basketId);
    }
}
