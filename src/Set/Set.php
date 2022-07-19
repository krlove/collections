<?php

declare(strict_types=1);

namespace Krlove\Collection\Set;

use ArrayIterator;
use Krlove\Collection\Exception\InvalidArgumentException;
use Krlove\Collection\Freeze\FreezeTrait;
use Krlove\Collection\Map\MapFactory;
use Krlove\Collection\Map\MapInterface;
use Krlove\Collection\Type\NullType;

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
        if (in_array($type, ['null', 'bool', 'iterable', 'callable', 'resource', 'mixed'])) {
            throw new InvalidArgumentException(sprintf('Type %s is not supported as a Set members type', $type));
        }

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

    public function difference(SetInterface $set): ?SetInterface
    {
        $diffSet = self::of($this->getType());

        foreach ($this as $member) {
            if (!$set->has($member)) {
                $diffSet->add($member);
            }
        }

        return $diffSet;
    }

    public function getType(): string
    {
        return $this->map->getKeyType();
    }

    public function has($member): bool
    {
        return $this->map->has($member);
    }

    public function hasIntersectionWith(SetInterface $set): bool
    {
        if ($this->getType() !== $set->getType()) {
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
        $intersectionSet = self::of($this->getType());

        if ($this->getType() !== $set->getType()) {
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
        if ($this->getType() !== $set->getType()) {
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
        if ($this->getType() !== $set->getType()) {
            throw new InvalidArgumentException(
                sprintf('Union of sets of types %s and %s is not supported', $this->getType(), $set->getType())
            );
        }

        $unionSet = clone $this;
        foreach ($set as $member) {
            $unionSet->add($member);
        }

        return $unionSet;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->map->keys());
    }

    public function count()
    {
        return $this->map->count();
    }
}