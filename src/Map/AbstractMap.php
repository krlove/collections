<?php

declare(strict_types=1);

namespace Krlove\Collections\Map;

use ArrayIterator;
use Krlove\Collections\Exception\OutOfBoundsException;
use Krlove\Collections\Freezable\FreezeTrait;
use Krlove\Collections\Type\TypeInterface;
use function call_user_func;

abstract class AbstractMap implements MapInterface
{
    use FreezeTrait;

    protected TypeInterface $valueType;
    protected TypeInterface $keyType;

    public function __construct(TypeInterface $keyType, TypeInterface $valueType)
    {
        $this->keyType = $keyType;
        $this->valueType = $valueType;
    }

    public function copy(): self
    {
        $map = new static($this->keyType, $this->valueType);

        foreach ($this as $pair) {
            $map->set($pair->getKey(), $pair->getValue());
        }

        return $map;
    }

    public function filter(callable $callable): MapInterface
    {
        $map = Map::of((string) $this->getKeyType(), (string) $this->getValueType());
        /** @var Pair $pair */
        foreach ($this as $pair) {
            if (call_user_func($callable, $pair) === true) {
                $map->set($pair->getKey(), $pair->getValue());
            }
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
        return (string)$this->getKeyType() === $type;
    }

    public function isOf(string $keyType, string $valueType): bool
    {
        return $this->isKeyOf($keyType) && $this->isValueOf($valueType);
    }

    public function isValueOf(string $type): bool
    {
        return (string)$this->getValueType() === $type;
    }

    public function hasValue($value): bool
    {
        try {
            $this->keyOf($value);
        } catch (OutOfBoundsException $e) {
            return false;
        }

        return true;
    }

    public function map(callable $callable): array
    {
        $result = [];
        foreach ($this as $pair) {
            $result[] = call_user_func($callable, $pair);
        }

        return $result;
    }

    public function reduce(callable $callable, $initial)
    {
        foreach ($this as $pair) {
            $initial = call_user_func($callable, $pair, $initial);
        }

        return $initial;
    }

    public function removeValue($value): bool
    {
        $this->assertNotFrozen();

        try {
            $key = $this->keyOf($value);
        } catch (OutOfBoundsException $e) {
            return false;
        }

        return $this->remove($key);
    }

    public function walk(callable $callable): void
    {
        foreach ($this as $pair) {
            call_user_func($callable, $pair);
        }
    }
}