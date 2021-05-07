<?php

declare(strict_types=1);

namespace App\Domain\Validator;

use App\Domain\BasketInterface;
use App\Domain\BasketItemDTOInterface;

class BasketFreeCapacityValidator
{
    /** @param BasketItemDTOInterface[] $items */
    public function validate(BasketInterface $basket, array $items): void
    {
        $newItemsTotalWeight = 0;
        foreach ($items as $item) {
            $newItemsTotalWeight += $item->getWeight();
        }

        if ($newItemsTotalWeight > $basket->getFreeCapacity()) {
            throw new \RuntimeException(message: 'Total weight of Items is greater than free capacity of Basket');
        }
    }
}
