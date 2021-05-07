<?php

declare(strict_types=1);

namespace App\Domain;

interface BasketItemDTOInterface
{
    public function getType(): string;

    public function getWeight(): float;
}
