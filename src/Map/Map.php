<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

class Map implements MapInterface
{
    private MapInterface $realMap;

    private function __construct(MapInterface $realMap)
    {
        $this->realMap = $realMap;
    }

    public static function of(string $keyType, string $valueType): self
    {
        return new self(MapFactory::create($keyType, $valueType));
    }

    public function clear(): void
    {
        $this->realMap->clear();
    }

    public function copy(): self
    {
        $realMap = $this->realMap->copy();

        return new Map($realMap);
    }

    public function count(): int
    {
        return $this->realMap->count();
    }

    public function freeze(): void
    {
        $this->realMap->freeze();
    }

    public function get($key)
    {
        return $this->realMap->get($key);
    }

    public function getIterator()
    {
        return $this->realMap->getIterator();
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

    public function isEmpty(): bool
    {
        return $this->realMap->isEmpty();
    }

    public function isFrozen(): bool
    {
        return $this->realMap->isFrozen();
    }

    public function isKeyOf(string $type): bool
    {
        return $this->realMap->isKeyOf($type);
    }

    public function isValueOf(string $type): bool
    {
        return $this->realMap->isValueOf($type);
    }

    public function isOf(string $keyType, string $valueType): bool
    {
        return $this->realMap->isOf($keyType, $valueType);
    }

    public function keyOf($value)
    {
        return $this->realMap->keyOf($value);
    }

    public function keys(): array
    {
        return $this->realMap->keys();
    }

    public function remove($key): bool
    {
        return $this->realMap->remove($key);
    }

    public function removeValue($value): bool
    {
        return $this->realMap->removeValue($value);
    }

    public function set($key, $value): void
    {
        $this->realMap->set($key, $value);
    }

    public function setMultiple(array $array): void
    {
        $this->realMap->setMultiple($array);
    }

    public function toArray(): array
    {
        return $this->realMap->toArray();
    }

    public function values(): array
    {
        return $this->realMap->values();
    }
}