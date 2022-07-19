<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use Krlove\Collection\Exception\OutOfBoundsException;
use Krlove\Collection\Type\FloatType;
use Krlove\Collection\Type\TypeInterface;

class FloatKeyMap extends AbstractScalarKeyMap
{
    public function __construct(TypeInterface $valueType)
    {
        parent::__construct($valueType);

        $this->keyType = new FloatType();
    }

    public function count(): int
    {
        return count($this->array);
    }

    public function get($key)
    {
        $key = $this->normalizeKey($key);

        if (!$this->has($key)) {
            throw new OutOfBoundsException(sprintf('Key %d does not exist', $key));
        }

        return $this->array[$key];
    }

    public function has($key): bool
    {
        $key = $this->normalizeKey($key);

        return array_key_exists($key, $this->array);
    }

    public function keyOf($value)
    {
        $key = array_search($value, $this->array, true);

        if ($key === false) {
            return null;
        }

        return $this->denormalizeKey($key);
    }

    public function keys(): array
    {
        return array_keys($this->toArray());
    }

    public function remove($key): bool
    {
        $this->assertNotFrozen();

        if ($this->has($key)) {
            $key = $this->normalizeKey($key);

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

        $key = $this->normalizeKey($key);

        $this->array[$key] = $value;
    }

    public function toArray(): array
    {
        $pairs = [];

        foreach ($this->array as $key => $value) {
            $pairs[] = new Pair($this->denormalizeKey($key), $value);
        }

        return $pairs;
    }

    protected function normalizeKey(float $key): string
    {
        return (string) floatval($key);
    }

    protected function denormalizeKey(string $key): float
    {
        return floatval($key);
    }
}
