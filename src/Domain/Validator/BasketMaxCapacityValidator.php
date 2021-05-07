<?php

declare(strict_types=1);

namespace App\Domain\Validator;

use App\Domain\BasketDTOInterface;
use App\Domain\BasketUpdateDTOInterface;

class BasketMaxCapacityValidator
{
    protected const CAPACITY_MAX_VALUE = 999.999;
    protected const CAPACITY_DECIMAL_PLACES = 3;

    public function validate(BasketDTOInterface | BasketUpdateDTOInterface $basket): void
    {
        if (!empty($basket->getMaxCapacity())) {
            if ($basket->getMaxCapacity() > self::CAPACITY_MAX_VALUE) {
                throw new \InvalidArgumentException(message: "Maximum 'maxCapacity' value is ".self::CAPACITY_MAX_VALUE);
            }

            if (strlen(substr(strrchr((string) $basket->getMaxCapacity(), '.'), 1)) > self::CAPACITY_DECIMAL_PLACES) {
                throw new \InvalidArgumentException(message: "Maximum decimal places in 'maxCapacity' value is ".self::CAPACITY_DECIMAL_PLACES);
            }
        }
    }
}
