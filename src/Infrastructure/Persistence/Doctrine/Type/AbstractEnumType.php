<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use function in_array;
use InvalidArgumentException;

abstract class AbstractEnumType extends Type
{
    protected string $name;

    public const VALUES = [];

    abstract protected function getValues(): array;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $values = array_map(function ($val) { return "'".$val."'"; }, $this->getValues());

        return 'ENUM('.implode(', ', $values).')';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!in_array($value, $this->getValues(), true)) {
            throw new InvalidArgumentException("Invalid '".$this->name."' value.");
        }

        return $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
