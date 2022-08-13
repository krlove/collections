<?php

declare(strict_types=1);

namespace Krlove\Collections\Set;

use Countable;
use IteratorAggregate;
use Krlove\Collections\Exception\FrozenException;
use Krlove\Collections\Exception\OutOfBoundsException;
use Krlove\Collections\Exception\TypeException;
use Krlove\Collections\Freezable\FreezableInterface;
use Krlove\Collections\Type\TypeInterface;

/**
 * Collection of unique variables of specified type
 *
 * @psalm-template T
 * @template-extends IteratorAggregate<T>
 */
interface SetInterface extends Countable, FreezableInterface, IteratorAggregate
{
    /**
     * Adds a member to the set. If member already exists in the set, it won't be added twice
     *
     * O(1) operation
     *
     * @param T $member
     * @psalm-param T $member
     * @return void
     * @throws FrozenException
     * @throws TypeException
     */
    public function add($member): void;

    /**
     * Adds multiple members to the set
     *
     * O(N) operation, where N is the number of added members
     *
     * @param T[] $members
     * @psalm-param T[] $members
     * @return void
     * @throws FrozenException
     * @throws TypeException
     */
    public function addMultiple($members): void;

    /**
     * Clears the set
     *
     * O(1) operation
     *
     * @return void
     * @throws FrozenException
     */
    public function clear(): void;

    /**
     * Checks whether the given member exists in the set
     *
     * O(1) operation
     *
     * @param T $member
     * @psalm-param T $member
     * @return bool
     */
    public function contains($member): bool;

    /**
     * Copies the set. The copy is always unfrozen
     *
     * O(N) operation
     *
     * @return SetInterface
     */
    public function copy(): self;

    /**
     * Returns new set, containing the difference between two sets
     *
     * O(N) operation
     *
     * @param SetInterface $set
     * @return SetInterface
     * @throws TypeException
     */
    public function difference(SetInterface $set): SetInterface;

    /**
     * Returns the type of the sequence
     *
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * Returns true, if two sets intersect with each other, false otherwise
     *
     * O(N) operation
     *
     * @param SetInterface $set
     * @return bool
     */
    public function hasIntersectionWith(SetInterface $set): bool;

    /**
     * Returns new set, containing the intersection of two sets
     *
     * O(N) operation
     *
     * @param SetInterface $set
     * @return SetInterface
     * @throws TypeException
     */
    public function intersection(SetInterface $set): SetInterface;

    /**
     * Returns true if the set is empty, false otherwise
     *
     * O(1) operation
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Returns true if the set type is $type, false otherwise
     *
     * @param string $type
     * @return bool
     */
    public function isOf(string $type): bool;

    /**
     * Returns true, if this set is a subset of the given set, false otherwise
     *
     * O(N) operation
     *
     * @param SetInterface $set
     * @return bool
     */
    public function isSubsetOf(SetInterface $set): bool;

    /**
     * Pops random element off the set
     *
     * O(1) operation
     *
     * @return T
     * @psalm-return T
     * @throws FrozenException
     * @throws OutOfBoundsException
     */
    public function pop();

    /**
     * Removes the member from the set. Returns true if the member existed in the set, false otherwise
     *
     * O(1) operation
     *
     * @param T $member
     * @psalm-param T $member
     * @return bool
     * @throws FrozenException
     */
    public function remove($member): bool;

    /**
     * Returns members as array
     *
     * O(N) operation
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Returns new set, which is the union of two sets
     *
     * O(N) operation
     *
     * @param SetInterface $set
     * @return SetInterface
     * @throws TypeException
     */
    public function union(SetInterface $set): SetInterface;
}
