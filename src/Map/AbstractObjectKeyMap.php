<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use Krlove\Collection\Exception\CollectionException;
use Krlove\Collection\Exception\OutOfBoundsException;
use Krlove\Collection\Exception\TypeException;
use Krlove\Collection\Iterator\ObjectStorageIterator;
use Krlove\Collection\Type\TypeInterface;
use SplObjectStorage;

abstract class AbstractObjectKeyMap extends AbstractMap
{
    protected SplObjectStorage $objectStorage;

    public function __construct(TypeInterface $valueType)
    {
        $this->valueType = $valueType;
        $this->objectStorage = new SplObjectStorage();
    }

    public function clear(): void
    {
        $this->objectStorage->removeAll($this->objectStorage);
    }

    public function count(): int
    {
        return $this->objectStorage->count();
    }

    public function get($key)
    {
        if (!$this->has($key)) {
            throw new OutOfBoundsException(sprintf('Key %d does not exist', $key));
        }

        return $this->objectStorage->offsetGet($key);
    }

    public function getIterator()
    {
        return new ObjectStorageIterator($this->objectStorage);
    }

    public function has($key): bool
    {
        if (!is_object($key)) {
            throw new TypeException('Key must be an object');
        }

        return $this->objectStorage->contains($key);
    }

    public function hasValue($value): bool
    {
        if ($this->valueType->isTypeOf($value)) {
            return false;
        }

        // todo check this
        foreach ($this->objectStorage as $object => $info) {
            if ($info === $value) {
                return true;
            }
        }

        return false;
    }

    public function keyOf($value)
    {
        if (!$this->valueType->isTypeOf($value)) {
            return null;
        }

        foreach ($this->objectStorage as $object => $info) {
            if ($info === $value) {
                return $object;
            }
        }

        return null;
    }

    public function keys(): array
    {
        return iterator_to_array($this->objectStorage);
    }

    public function remove($key): bool
    {
        if ($this->has($key)) {
            $this->objectStorage->offsetUnset($key);

            return true;
        }

        return false;
    }

    public function set($key, $value): void
    {
        $this->assertNotFrozen();

        $this->keyType->assertIsTypeOf($key);
        $this->valueType->assertIsTypeOf($value);

        $this->objectStorage->attach($key, $value);
    }

    public function setMultiple(array $array): void
    {
        throw new CollectionException('Method setMultiple is not supported by Map with object keys');
    }

    public function toArray(): array
    {
        $pairs = [];

        foreach ($this->objectStorage as $object) {
            $pairs[] = new Pair($object, $this->objectStorage->getInfo());
        }

        return $pairs;
    }

    public function values(): array
    {
        $values = [];

        foreach ($this->objectStorage as $value) {
            $values[] = $this->objectStorage->getInfo();
        }

        return $values;
    }
}