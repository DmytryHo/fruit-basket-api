<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Validator\BasketFreeCapacityValidator;
use App\Domain\Validator\BasketItemTypeValidator;
use App\Domain\Validator\BasketItemWeightValidator;

class BasketItemService
{
    public function __construct(
        protected BasketStorageInterface $basketStorage,
        protected BasketItemWeightValidator $basketItemWeightValidator,
        protected BasketItemTypeValidator $basketItemTypeValidator,
        protected BasketFreeCapacityValidator $basketFreeCapacityValidator,
    ) {
    }

    public function cleanBasketById(int $basketId): bool
    {
        return $this->basketStorage->cleanBasketById($basketId);
    }

    /** @param BasketItemDTOInterface[] $items */
    public function addItemToBasketById(int $basketId, array $items): bool
    {
        foreach ($items as $item) {
            $this->basketItemTypeValidator->validate($item);
            $this->basketItemWeightValidator->validate($item);
        }
        $basket = $this->basketStorage->getBasketById($basketId);
        if (empty($basket)) {
            return false;
        }
        $this->basketFreeCapacityValidator->validate($basket, $items);

        return $this->basketStorage->addItemsToBasketById($basketId, $items);
    }
}
