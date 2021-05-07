<?php

declare(strict_types=1);

namespace App\Domain;

interface BasketDTOInterface
{
    public function getName(): string;

    public function getMaxCapacity(): float;
}
