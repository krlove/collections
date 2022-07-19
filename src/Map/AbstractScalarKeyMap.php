<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use ArrayIterator;
use Krlove\Collection\Exception\OutOfBoundsException;
use Krlove\Collection\Type\TypeInterface;

abstract class AbstractScalarKeyMap extends AbstractMap
{
    protected TypeInterface $valueType;
    protected TypeInterface $keyType;
    protected array $array = [];

    public function __construct(TypeInterface $valueType)
    {
        $this->valueType = $valueType;
    }

    public function clear(): void
    {
        $this->assertNotFrozen();

        $this->array = [];
    }

    public function count(): int
    {
        return count($this->array);
    }

    public function get($key)
    {
        if (!$this->has($key)) {
            throw new OutOfBoundsException(sprintf('Key %d does not exist', $key));
        }

        return $this->array[$key];
    }

    public function getIterator()
    {
        return new ArrayIterator($this->array);
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->array);
    }

    public function hasValue($value): bool
    {
        return $this->keyOf($value) !== null;
    }

    public function keyOf($value)
    {
        $key = array_search($value, $this->array, true);

        if ($key === false) {
            return null;
        }

        return $key;
    }

    public function keys(): array
    {
        return array_keys($this->array);
    }

    public function remove($key): bool
    {
        $this->assertNotFrozen();

        if ($this->has($key)) {
            unset($this->array[$key]);

            return true;
        }

        return false;
    }

    public function set($key, $value): void
    {
        $this->assertNotFrozen();

        $this->keyType->assertIsTypeOf($key);
        $this->valueType->assertIsTypeOf($value);

        $this->array[$key] = $value;
    }

    public function setMultiple(array $array): void
    {
        $this->assertNotFrozen();

        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function toArray(): array
    {
        $pairs = [];

        foreach ($this->array as $key => $value) {
            $pairs[] = new Pair($key, $value);
        }

        return $pairs;
    }

    public function values(): array
    {
        return array_values($this->array);
    }
}