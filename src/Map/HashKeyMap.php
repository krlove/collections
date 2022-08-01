<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use Krlove\Collection\Exception\OutOfBoundsException;
use Krlove\Collection\Iterator\MapIterator;
use Krlove\Collection\Hasher\Hasher;
use Krlove\Collection\Type\TypeInterface;

class HashKeyMap extends AbstractMap
{
    private array $ks = [];
    private array $vs = [];

    public function __construct(TypeInterface $keyType, TypeInterface $valueType)
    {
        $this->keyType = $keyType;
        $this->valueType = $valueType;
    }

    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->vs);
    }

    public function clear(): void
    {
        $this->assertNotFrozen();

        $this->ks = [];
        $this->vs = [];
    }

    public function get($key)
    {
        if (!$this->has($key)) {
            throw new OutOfBoundsException(sprintf('Key %d does not exist', $key));
        }

        $hashedKey = Hasher::hash($key);

        return $this->vs[$hashedKey];
    }

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new MapIterator($this->ks, $this->vs);
    }

    public function has($key): bool
    {
        $hashedKey = Hasher::hash($key);

        return array_key_exists($hashedKey, $this->ks);
    }

    public function keyOf($value)
    {
        if (!$this->valueType->isTypeOf($value)) {
            return null;
        }

        $hashedKey = array_search($value, $this->vs, true);

        if ($hashedKey === false) {
            return null;
        }

        return $this->ks[$hashedKey];
    }

    public function keys(): array
    {
        return array_values($this->ks);
    }

    public function remove($key): bool
    {
        $this->assertNotFrozen();

        if ($this->has($key)) {
            $hashedKey = Hasher::hash($key);
            unset($this->ks[$hashedKey]);
            unset($this->vs[$hashedKey]);

            return true;
        }

        return false;
    }

    public function set($key, $value): void
    {
        $this->assertNotFrozen();

        $this->keyType->assertIsTypeOf($key);
        $this->valueType->assertIsTypeOf($value);

        $hashedKey = Hasher::hash($key);

        $this->ks[$hashedKey] = $key;
        $this->vs[$hashedKey] = $value;
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this->ks as $hashedKey => $key) {
            $array[] = new Pair($key, $this->vs[$hashedKey]);
        }

        return $array;
    }

    public function values(): array
    {
        return array_values($this->vs);
    }
}
