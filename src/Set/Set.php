<?php

declare(strict_types=1);

namespace Krlove\Collection\Set;

use ArrayIterator;
use Krlove\Collection\Exception\TypeException;
use Krlove\Collection\Freezable\FreezeTrait;
use Krlove\Collection\Map\MapFactory;
use Krlove\Collection\Map\MapInterface;
use Krlove\Collection\Type\TypeInterface;

class Set implements SetInterface
{
    use FreezeTrait;

    private MapInterface $map;

    public function __construct(MapInterface $map)
    {
        $this->map = $map;
    }

    public static function of(string $type): self
    {
        return new self(MapFactory::create($type, 'null'));
    }

    public function add($member): void
    {
        $this->assertNotFrozen();

        $this->map->set($member, null);
    }

    public function addMultiple($members): void
    {
        $this->assertNotFrozen();

        foreach ($members as $member) {
            $this->map->set($member, null);
        }
    }

    public function clear(): void
    {
        $this->assertNotFrozen();

        $this->map->clear();
    }

    public function copy(): self
    {
        $set = Set::of((string) $this->getType());

        foreach ($this as $member) {
            $set->add($member);
        }

        return $set;
    }

    public function count()
    {
        return $this->map->count();
    }

    public function difference(SetInterface $set): ?SetInterface
    {
        $diffSet = self::of((string) $this->getType());

        foreach ($this as $member) {
            if (!$set->has($member)) {
                $diffSet->add($member);
            }
        }

        return $diffSet;
    }

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->map->keys());
    }

    public function getType(): TypeInterface
    {
        return $this->map->getKeyType();
    }

    public function has($member): bool
    {
        return $this->map->has($member);
    }

    public function hasIntersectionWith(SetInterface $set): bool
    {
        if ($this->getType()->getType() !== $set->getType()->getType()) {
            return false;
        }

        if ($this->count() < $set->count()) {
            $iterated = $this;
            $lookedUp = $set;
        } else {
            $iterated = $set;
            $lookedUp = $this;
        }

        foreach ($iterated as $member) {
            if ($lookedUp->has($member)) {
                return true;
            }
        }

        return false;
    }

    public function intersection(SetInterface $set): SetInterface
    {
        $intersectionSet = self::of((string) $this->getType());

        if ($this->getType()->getType() !== $set->getType()->getType()) {
            return $intersectionSet;
        }

        if ($this->count() < $set->count()) {
            $iterated = $this;
            $lookedUp = $set;
        } else {
            $iterated = $set;
            $lookedUp = $this;
        }

        foreach ($iterated as $member) {
            if ($lookedUp->has($member)) {
                $intersectionSet->add($member);
            }
        }

        return $intersectionSet;
    }

    public function isEmpty(): bool
    {
        return $this->map->isEmpty();
    }

    public function isOf(string $type): bool
    {
        return $this->map->isKeyOf($type);
    }

    public function isSubsetOf(SetInterface $set): bool
    {
        if ($this->getType()->getType() !== $set->getType()->getType()) {
            return false;
        }

        if ($this->count() > $set->count()) {
            return false;
        }

        foreach ($this as $member) {
            if (!$set->has($member)) {
                return false;
            }
        }

        return true;
    }

    public function remove($member): bool
    {
        $this->assertNotFrozen();

        return $this->map->remove($member);
    }

    public function toArray(): array
    {
        return $this->map->keys();
    }

    public function union(SetInterface $set): SetInterface
    {
        if ($this->getType()->getType() !== $set->getType()->getType()) {
            throw new TypeException(
                sprintf('Union of sets of types %s and %s is not supported', $this->getType(), $set->getType())
            );
        }

        $unionType = $this->getType()->getType();
        if ($this->getType()->isNullable() || $set->getType()->isNullable()) {
            $unionType = '?' . $unionType;
        }

        $unionSet = self::of($unionType);
        foreach ($this as $member) {
            $unionSet->add($member);
        }
        foreach ($set as $member) {
            $unionSet->add($member);
        }

        return $unionSet;
    }
}