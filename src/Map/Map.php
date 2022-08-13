<?php

declare(strict_types=1);

namespace Krlove\Collections\Map;

use Krlove\Collections\Freezable\FreezeTrait;
use Krlove\Collections\Type\TypeInterface;

/**
 * @psalm-template TKey
 * @psalm-template TValue
 * @template-implements MapInterface<TKey, TValue>
 */
class Map implements MapInterface
{
    use FreezeTrait;

    private MapInterface $realMap;

    private function __construct(MapInterface $realMap)
    {
        $this->realMap = $realMap;
    }

    public static function of(string $keyType, string $valueType): self
    {
        return new self(MapFactory::create($keyType, $valueType));
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): void
    {
        $this->assertNotFrozen();

        $this->realMap->clear();
    }

    /**
     * {@inheritDoc}
     */
    public function copy(): self
    {
        $realMap = $this->realMap->copy();

        return new Map($realMap);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return $this->realMap->count();
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->realMap->get($key);
    }

    /**
     * {@inheritDoc}
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return $this->realMap->getIterator();
    }

    /**
     * {@inheritDoc}
     */
    public function getKeyType(): TypeInterface
    {
        return $this->realMap->getKeyType();
    }

    /**
     * {@inheritDoc}
     */
    public function getValueType(): TypeInterface
    {
        return $this->realMap->getValueType();
    }

    /**
     * {@inheritDoc}
     */
    public function has($key): bool
    {
        return $this->realMap->has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function hasValue($value): bool
    {
        return $this->realMap->hasValue($value);
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return $this->realMap->isEmpty();
    }

    /**
     * {@inheritDoc}
     */
    public function isKeyOf(string $type): bool
    {
        return $this->realMap->isKeyOf($type);
    }

    /**
     * {@inheritDoc}
     */
    public function isValueOf(string $type): bool
    {
        return $this->realMap->isValueOf($type);
    }

    /**
     * {@inheritDoc}
     */
    public function isOf(string $keyType, string $valueType): bool
    {
        return $this->realMap->isOf($keyType, $valueType);
    }

    /**
     * {@inheritDoc}
     */
    public function keyOf($value)
    {
        return $this->realMap->keyOf($value);
    }

    /**
     * {@inheritDoc}
     */
    public function keys(): array
    {
        return $this->realMap->keys();
    }

    /**
     * {@inheritDoc}
     */
    public function pop(): Pair
    {
        $this->assertNotFrozen();

        return $this->realMap->pop();
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key): bool
    {
        $this->assertNotFrozen();

        return $this->realMap->remove($key);
    }

    /**
     * {@inheritDoc}
     */
    public function removeValue($value): bool
    {
        $this->assertNotFrozen();

        return $this->realMap->removeValue($value);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value): void
    {
        $this->assertNotFrozen();

        $this->realMap->set($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->realMap->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function values(): array
    {
        return $this->realMap->values();
    }
}