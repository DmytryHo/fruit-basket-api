<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\BasketDTOInterface;

class BasketDTO implements BasketDTOInterface
{
    public function __construct(
        protected string $name,
        protected float $maxCapacity,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMaxCapacity(): float
    {
        return $this->maxCapacity;
    }
}
