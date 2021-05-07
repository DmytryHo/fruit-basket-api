<?php

declare(strict_types=1);

namespace App\Domain\Validator;

use App\Domain\BasketItemDTOInterface;
use App\Infrastructure\Persistence\Doctrine\Type\BasketItemTypeEnum;

class BasketItemTypeValidator
{
    public function validate(BasketItemDTOInterface $basket): void
    {
        if (!in_array($basket->getType(), BasketItemTypeEnum::VALUES)) {
            throw new \InvalidArgumentException(message: "Invalid 'type' value. Available values: ".implode(', ', BasketItemTypeEnum::VALUES));
        }
    }
}
