<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

class BasketItemTypeEnum extends AbstractEnumType
{
    public const VALUE_APPLE = 'apple';
    public const VALUE_ORANGE = 'orange';
    public const VALUE_WATERMELON = 'watermelon';

    public const VALUES = [
        self::VALUE_APPLE,
        self::VALUE_ORANGE,
        self::VALUE_WATERMELON,
    ];

    protected string $name = 'BasketItemTypeEnum';

    protected function getValues(): array
    {
        return self::VALUES;
    }
}
