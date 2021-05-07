<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\BasketItemDTOInterface;

class BasketItemDTO implements BasketItemDTOInterface
{
    public function __construct(
        protected string $type,
        protected float $weight,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }
}
