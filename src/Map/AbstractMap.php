<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use Krlove\Collection\Freezable\FreezeTrait;
use Krlove\Collection\Type\TypeInterface;

abstract class AbstractMap implements MapInterface
{
    use FreezeTrait;

    protected TypeInterface $valueType;
    protected TypeInterface $keyType;

    public function copy(): self
    {
        $map = new static($this->keyType, $this->valueType);

        foreach ($this as $key => $value) {
            $map->set($key, $value);
        }

        return $map;
    }

    public function getKeyType(): TypeInterface
    {
        return $this->keyType;
    }

    public function getValueType(): TypeInterface
    {
        return $this->valueType;
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function isKeyOf(string $type): bool
    {
        return (string) $this->getKeyType() === $type;
    }

    public function isOf(string $keyType, string $valueType): bool
    {
        return $this->isKeyOf($keyType) && $this->isValueOf($valueType);
    }

    public function isValueOf(string $type): bool
    {
        return (string) $this->getValueType() === $type;
    }

    public function hasValue($value): bool
    {
        return $this->keyOf($value) !== null;
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

    public function setMultiple(array $array): void
    {
        $this->assertNotFrozen();

        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }
}