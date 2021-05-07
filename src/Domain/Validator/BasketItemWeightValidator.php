<?php

declare(strict_types=1);

namespace App\Domain\Validator;

use App\Domain\BasketItemDTOInterface;

class BasketItemWeightValidator
{
    protected const WEIGHT_MAX_VALUE = 99.999;
    protected const WEIGHT_DECIMAL_PLACES = 3;

    public function validate(BasketItemDTOInterface $basket): void
    {
        if (!empty($basket->getWeight())) {
            if ($basket->getWeight() > self::WEIGHT_MAX_VALUE) {
                throw new \InvalidArgumentException(message: "Maximum 'weight' value is ".self::WEIGHT_MAX_VALUE);
            }

            if (strlen(substr(strrchr((string) $basket->getWeight(), '.'), 1)) > self::WEIGHT_DECIMAL_PLACES) {
                throw new \InvalidArgumentException(message: "Maximum decimal places in 'weight' value is ".self::WEIGHT_DECIMAL_PLACES);
            }
        }
    }
}
