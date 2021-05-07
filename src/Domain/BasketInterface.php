<?php

declare(strict_types=1);

namespace App\Domain;

interface BasketInterface extends \JsonSerializable
{
    public function getId(): int;

    public function getFreeCapacity(): float;

    public function getItems(): array;
}
