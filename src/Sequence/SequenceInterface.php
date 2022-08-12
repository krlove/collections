<?php

declare(strict_types=1);

namespace Krlove\Collections\Sequence;

use Countable;
use IteratorAggregate;
use Krlove\Collections\Exception\FrozenException;
use Krlove\Collections\Exception\OutOfBoundsException;
use Krlove\Collections\Exception\TypeException;
use Krlove\Collections\Freezable\FreezableInterface;
use Krlove\Collections\Type\TypeInterface;

/**
 * Ordered collection of entries of the specified type
 *
 * @psalm-template T
 * @template-extends IteratorAggregate<T>
 */
interface SequenceInterface extends Countable, FreezableInterface, IteratorAggregate
{
    /**
     * Clears the sequence
     *
     * O(1) operation
     *
     * @return void
     * @throws FrozenException
     */
    public function clear(): void;

    /**
     * Copies the sequence. Copy is always unfrozen
     *
     * O(N) operation
     *
     * @return SequenceInterface
     */
    public function copy(): self;

    /**
     * Returns the first entry in the sequence
     *
     * O(1) operation
     *
     * @return T
     * @psalm-return T
     * @throws OutOfBoundsException
     */
    public function first();

    /**
     * Returns an entry by its index
     *
     * O(N) operation
     *
     * @param int $index
     * @return T
     * @psalm-return T
     * @throws OutOfBoundsException
     */
    public function get(int $index);

    /**
     * Returns the type of the sequence
     *
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * Returns true, if the sequence contains an entry with given index, false otherwise
     *
     * O(1) operation
     *
     * @param int $index
     * @return bool
     */
    public function has(int $index): bool;

    /**
     * Returns true, if the sequence contains given entry, false otherwise
     *
     * O(N) operation
     *
     * @param T $entry
     * @psalm-param T $entry
     * @return bool
     */
    public function hasEntry($entry): bool;

    /**
     * Returns an index of given entry, if it exists in the sequence. If the sequence contains multiple entries,
     * then the index of the first found one will be returned
     *
     * O(N) operation
     *
     * @param T $entry
     * @psalm-param T $entry
     * @return int
     * @throws OutOfBoundsException
     */
    public function indexOf($entry): int;

    /**
     * Inserts the entry into given position (index). All following entries are shifted to the right
     *
     * O(N) operation
     *
     * @param int $index
     * @param T $entry
     * @psalm-param T $entry
     * @return void
     * @throws FrozenException
     * @throws TypeException
     * @throws OutOfBoundsException
     */
    public function insert(int $index, $entry): void;

    /**
     * Returns true if the sequence is empty
     *
     * O(1) operation
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Returns true if the sequence type is $type, false otherwise
     *
     * @param string $type
     * @return bool
     */
    public function isOf(string $type): bool;

    /**
     * Returns the first entry in the sequence
     *
     * O(1) operation
     *
     * @return T
     * @psalm-return T
     * @throws OutOfBoundsException
     */
    public function last();

    /**
     * Pops the entry off the end of the sequence
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
     * Pushes the entry onto the end of the sequence
     *
     * O(1) operation
     *
     * @param T $entry
     * @psalm-param T $entry
     * @return void
     * @throws FrozenException
     * @throws TypeException
     */
    public function push($entry): void;

    /**
     * Pushes multiple entries onto the end of the sequence
     *
     * O(N) operation, where N is the number of entries to push to the sequence
     *
     * @param T[] $entries
     * @psalm-param T[] $entries
     * @return void
     * @throws FrozenException
     * @throws TypeException
     */
    public function pushMultiple(array $entries): void;

    /**
     * Removes an entry by the index. Returns true, if given index existed in the sequence, false otherwise
     *
     * O(N) operation
     *
     * @param int $index
     * @return bool
     * @throws FrozenException
     */
    public function remove(int $index): bool;

    /**
     * Removes the entry from the sequence. Returns true, if given entry existed in the sequence, false otherwise
     *
     * O(N) operation
     *
     * @param T $entry
     * @psalm-param T $entry
     * @return bool
     * @throws FrozenException
     */
    public function removeEntry($entry): bool;

    /**
     * Shifts an entry off the beginning of the sequence
     *
     * O(1) operation
     *
     * @return T
     * @psalm-return T
     * @throws FrozenException
     */
    public function shift();

    /**
     * Returns array of entries in the Sequence
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Prepends the entry to the beginning of the sequence
     *
     * O(1) operation
     *
     * @param T $entry
     * @psalm-param T $entry
     * @return void
     * @throws FrozenException
     * @throws TypeException
     */
    public function unshift($entry): void;
}
