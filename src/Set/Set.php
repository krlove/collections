<?php

declare(strict_types=1);

namespace Krlove\Collection\Set;

use ArrayIterator;
use Krlove\Collection\Exception\OutOfBoundsException;
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

    #[\ReturnTypeWillChange]
    public function count()
    {
        return $this->map->count();
    }

    public function difference(SetInterface $set): SetInterface
    {
        $this->assertSameTypeWith($set, 'difference');

        $diffSet = self::of($this->getCommonTypeWith($set));

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
        $this->assertSameTypeWith($set, __METHOD__);

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
        $this->assertSameTypeWith($set, __METHOD__);

        $intersectionSet = self::of($this->getCommonTypeWith($set));

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
        $this->assertSameTypeWith($set, __METHOD__);

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

    public function pop()
    {
        $this->assertNotFrozen();

        try {
            $pair = $this->map->pop();

            return $pair->getKey();
        } catch (OutOfBoundsException $e) {
            throw new OutOfBoundsException('Can not pop from an empty Set');
        }
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
        $this->assertSameTypeWith($set, __METHOD__);

        $unionSet = self::of($this->getCommonTypeWith($set));

        foreach ($this as $member) {
            $unionSet->add($member);
        }
        foreach ($set as $member) {
            $unionSet->add($member);
        }

        return $unionSet;
    }

    /**
     * todo: Types must be compared more thoroughly
     */
    private function assertSameTypeWith(SetInterface $set, string $operation): void
    {
        if ($this->getType()->getType() !== $set->getType()->getType()) {
            throw new TypeException(
                sprintf(
                    'To perform %s operation, sets must be of the same types, %s and %s given',
                    $operation,
                    $this->getType(),
                    $set->getType()
                )
            );
        }
    }

    private function getCommonTypeWith(SetInterface $set): string
    {
        $type = $this->getType()->getType();
        if ($this->getType()->isNullable() || $set->getType()->isNullable()) {
            $type = '?' . $type;
        }

        return $type;
    }
}