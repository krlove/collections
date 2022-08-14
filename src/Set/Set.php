<?php

declare(strict_types=1);

namespace Krlove\Collections\Set;

use ArrayIterator;
use Krlove\Collections\Exception\OutOfBoundsException;
use Krlove\Collections\Exception\TypeException;
use Krlove\Collections\Freezable\FreezeTrait;
use Krlove\Collections\Map\MapFactory;
use Krlove\Collections\Map\MapInterface;
use Krlove\Collections\Type\TypeInterface;
use Krlove\Collections\Type\TypeIntersection;
use function call_user_func;
use function sprintf;

/**
 * @psalm-template T
 * @template-implements SetInterface<T>
 */
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

    /**
     * {@inheritDoc}
     */
    public function add($member): void
    {
        $this->assertNotFrozen();

        $this->map->set($member, null);
    }

    /**
     * {@inheritDoc}
     */
    public function addMultiple($members): void
    {
        $this->assertNotFrozen();

        foreach ($members as $member) {
            $this->map->set($member, null);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): void
    {
        $this->assertNotFrozen();

        $this->map->clear();
    }

    /**
     * {@inheritDoc}
     */
    public function contains($member): bool
    {
        return $this->map->has($member);
    }

    /**
     * {@inheritDoc}
     */
    public function copy(): self
    {
        $set = Set::of((string)$this->getType());

        foreach ($this as $member) {
            $set->add($member);
        }

        return $set;
    }

    /**
     * {@inheritDoc}
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return $this->map->count();
    }

    /**
     * {@inheritDoc}
     */
    public function difference(SetInterface $set): SetInterface
    {
        $diffSet = self::of($this->getCommonTypeWith($set));

        foreach ($this as $member) {
            if (!$set->contains($member)) {
                $diffSet->add($member);
            }
        }

        return $diffSet;
    }

    /**
     * {@inheritDoc}
     */
    public function filter(callable $callable): SetInterface
    {
        $set = Set::of((string) $this->getType());
        foreach ($this as $member) {
            if (call_user_func($callable, $member) === true) {
                $set->add($member);
            }
        }

        return $set;
    }

    /**
     * {@inheritDoc}
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->map->keys());
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): TypeInterface
    {
        return $this->map->getKeyType();
    }

    /**
     * {@inheritDoc}
     */
    public function hasIntersectionWith(SetInterface $set): bool
    {
        try {
            $this->getCommonTypeWith($set);
        } catch (TypeException $e) {
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
            if ($lookedUp->contains($member)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function intersection(SetInterface $set): SetInterface
    {
        $intersectionSet = self::of($this->getCommonTypeWith($set));

        if ($this->count() < $set->count()) {
            $iterated = $this;
            $lookedUp = $set;
        } else {
            $iterated = $set;
            $lookedUp = $this;
        }

        foreach ($iterated as $member) {
            if ($lookedUp->contains($member)) {
                $intersectionSet->add($member);
            }
        }

        return $intersectionSet;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return $this->map->isEmpty();
    }

    /**
     * {@inheritDoc}
     */
    public function isOf(string $type): bool
    {
        return $this->map->isKeyOf($type);
    }

    /**
     * {@inheritDoc}
     */
    public function isSubsetOf(SetInterface $set): bool
    {
        try {
            $this->getCommonTypeWith($set);
        } catch (TypeException $e) {
            return false;
        }

        if ($this->count() > $set->count()) {
            return false;
        }

        foreach ($this as $member) {
            if (!$set->contains($member)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function map(callable $callable): array
    {
        $result = [];
        foreach ($this as $member) {
            $result[] = call_user_func($callable, $member);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function reduce(callable $callable, $initial)
    {
        $carry = $initial;
        foreach ($this as $member) {
            $carry = call_user_func($callable, $carry, $member);
        }

        return $carry;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($member): bool
    {
        $this->assertNotFrozen();

        return $this->map->remove($member);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->map->keys();
    }

    /**
     * {@inheritDoc}
     */
    public function union(SetInterface $set): SetInterface
    {
        $unionSet = self::of($this->getCommonTypeWith($set));

        foreach ($this as $member) {
            $unionSet->add($member);
        }
        foreach ($set as $member) {
            $unionSet->add($member);
        }

        return $unionSet;
    }

    private function getCommonTypeWith(SetInterface $set): string
    {
        $type = TypeIntersection::between($this->getType(), $set->getType());

        if ($type === null) {
            throw new TypeException(
                sprintf(
                    'Unable to perform operation: types %s and %s are not compatible',
                    (string) $this->getType(),
                    (string) $set->getType()
                )
            );
        }

        return $type;
    }

    /**
     * {@inheritDoc}
     */
    public function walk(callable $callable): void
    {
        foreach ($this as $member) {
            call_user_func($callable, $member);
        }
    }
}