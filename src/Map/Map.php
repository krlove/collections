<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use Krlove\Collection\Exception\InvalidArgumentException;

class Map implements MapInterface
{
    private MapInterface $realMap;

    private function __construct(MapInterface $keyMap)
    {
        $this->realMap = $keyMap;
    }

    public static function of(string $keyType, string $valueType): self
    {
        if (in_array($keyType, ['null', 'bool', 'iterable', 'callable', 'resource'])) {
            throw new InvalidArgumentException('Type %s is not supported as a Map keys type', $keyType);
        }

        return new self(MapFactory::create($keyType, $valueType));
    }

    public function isOf(string $keyType, string $valueType): bool
    {
        return $this->realMap->isOf($keyType, $valueType);
    }

    public function isKeyOf(string $type): bool
    {
        return $this->realMap->isKeyOf($type);
    }

    public function isValueOf(string $type): bool
    {
        return $this->realMap->isValueOf($type);
    }

    public function set($key, $value): void
    {
        $this->realMap->set($key, $value);
    }

    public function setMultiple(array $array): void
    {
        $this->realMap->setMultiple($array);
    }

    public function get($key)
    {
        return $this->realMap->get($key);
    }

    public function getKeyType(): string
    {
        return $this->realMap->getKeyType();
    }

    public function getValueType(): string
    {
        return $this->realMap->getValueType();
    }

    public function has($key): bool
    {
        return $this->realMap->has($key);
    }

    public function hasValue($value): bool
    {
        return $this->realMap->hasValue($value);
    }

    public function remove($key): bool
    {
        return $this->realMap->remove($key);
    }

    public function removeValue($value): bool
    {
        return $this->realMap->removeValue($value);
    }

    public function keyOf($value)
    {
        return $this->realMap->keyOf($value);
    }

    public function toArray(): array
    {
        return $this->realMap->toArray();
    }

    public function keys(): array
    {
        return $this->realMap->keys();
    }

    public function values(): array
    {
        return $this->realMap->values();
    }

    public function clear(): void
    {
        $this->realMap->clear();
    }

    public function isEmpty(): bool
    {
        return $this->realMap->isEmpty();
    }

    public function count(): int
    {
        return $this->realMap->count();
    }

    public function getIterator()
    {
        return $this->realMap->getIterator();
    }

    public function freeze(): void
    {
        $this->realMap->freeze();
    }

    public function isFrozen(): bool
    {
        return $this->realMap->isFrozen();
    }
}