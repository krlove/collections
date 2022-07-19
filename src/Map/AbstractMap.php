<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use Krlove\Collection\Copyable\CopyTrait;
use Krlove\Collection\Freezable\FreezeTrait;
use Krlove\Collection\Type\TypeInterface;

abstract class AbstractMap implements MapInterface
{
    use CopyTrait;
    use FreezeTrait;

    protected TypeInterface $valueType;
    protected TypeInterface $keyType;

    public function getKeyType(): string
    {
        return (string) $this->keyType;
    }

    public function getValueType(): string
    {
        return (string) $this->valueType;
    }

    public function isOf(string $keyType, string $valueType): bool
    {
        return $this->isKeyOf($keyType) && $this->isValueOf($valueType);
    }

    public function isKeyOf(string $type): bool
    {
        return $this->getKeyType() === $type;
    }

    public function isValueOf(string $type): bool
    {
        return $this->getValueType() === $type;
    }

    public function removeValue($value): bool
    {
        $this->assertNotFrozen();

        $key = $this->keyOf($value);

        if ($key !== null) {
            return $this->remove($key);
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }
}