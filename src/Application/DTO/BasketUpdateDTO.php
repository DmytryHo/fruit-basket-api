<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\BasketUpdateDTOInterface;

class BasketUpdateDTO implements BasketUpdateDTOInterface
{
    public function __construct(
        protected ?string $name,
        protected ?float $maxCapacity,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getMaxCapacity(): ?float
    {
        return $this->maxCapacity;
    }
}
