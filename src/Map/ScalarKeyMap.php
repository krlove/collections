<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use ArrayIterator;
use Krlove\Collection\Exception\OutOfBoundsException;
use Krlove\Collection\Exception\TypeException;
use Krlove\Collection\Type\TypeInterface;

class ScalarKeyMap extends AbstractMap
{
    private array $array = [];

    public function __construct(TypeInterface $keyType, TypeInterface $valueType)
    {
        if (!in_array((string) $keyType, ['int', 'string'])) {
            throw new TypeException(sprintf('Only int or string types supported as key type for %s, %s given', get_class($this), $keyType));
        }

        $this->keyType = $keyType;
        $this->valueType = $valueType;
    }

    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->array);
    }

    public function clear(): void
    {
        $this->assertNotFrozen();

        $this->array = [];
    }

    public function get($key)
    {
        if (!$this->has($key)) {
            throw new OutOfBoundsException(sprintf('Key %s does not exist', $key));
        }

        return $this->array[$key];
    }

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->array);
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->array);
    }

    public function keyOf($value)
    {
        if (!$this->valueType->isTypeOf($value)) {
            throw new OutOfBoundsException('Value not found in the Map');
        }

        $key = array_search($value, $this->array, true);

        if ($key === false) {
            throw new OutOfBoundsException('Value not found in the Map');
        }

        return $key;
    }

    public function keys(): array
    {
        return array_keys($this->array);
    }

    public function pop(): Pair
    {
        $this->assertNotFrozen();

        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Can not pop from an empty Map');
        }

        $key = array_rand($this->array);
        $value = $this->array[$key];

        return new Pair($key, $value);
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

    public function toArray(): array
    {
        $array = [];
        foreach ($this->array as $key => $value) {
            $array[] = new Pair($key, $value);
        }

        return $array;
    }

    public function values(): array
    {
        return array_values($this->array);
    }
}