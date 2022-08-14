<?php

declare(strict_types=1);

namespace Krlove\Collections\Map;

use Countable;
use IteratorAggregate;
use Krlove\Collections\Exception\FrozenException;
use Krlove\Collections\Exception\OutOfBoundsException;
use Krlove\Collections\Freezable\FreezableInterface;
use Krlove\Collections\Type\TypeInterface;
use Traversable;

/**
 * Contains key-value pairs, where each key is unique
 *
 * @psalm-template TKey
 * @psalm-template TValue
 * @template-implements IteratorAggregate<Pair<TKey, TValue>>
 */
interface MapInterface extends Countable, FreezableInterface, IteratorAggregate
{
    /**
     * Clears the map
     *
     * O(1) operation
     *
     * @return void
     * @throws FrozenException
     */
    public function clear(): void;

    /**
     * Copies the map. The copy is always unfrozen
     *
     * O(N) operation
     *
     * @return MapInterface
     */
    public function copy(): self;

    /**
     * Returns new instance of the map, filtered using the given callable.
     * Callable takes $pair as an argument and must return true so that the key-value pair is added to the
     * resulted map
     *
     * O(N) operation
     *
     * @param callable $callable
     * @return MapInterface
     */
    public function filter(callable $callable): MapInterface;

    /**
     * Returns a value by given key
     *
     * O(1) operation
     *
     * @param TKey $key
     * @psalm-param TKey $key
     * @return TValue
     * @psalm-return TValue
     * @throws OutOfBoundsException
     */
    public function get($key);

    /**
     * O(N) operation
     *
     * @return Traversable<Pair<TKey, TValue>>
     */
    public function getIterator();

    /**
     * Get the type of keys in the map
     *
     * @return TypeInterface
     */
    public function getKeyType(): TypeInterface;

    /**
     * Get the type of values in the map
     *
     * @return TypeInterface
     */
    public function getValueType(): TypeInterface;

    /**
     * Returns true, if the given key exists in the map, false otherwise
     *
     * O(1) operation
     *
     * @param TKey $key
     * @psalm-param TKey $key
     * @return bool
     */
    public function has($key): bool;

    /**
     * Return true, of the given value exists in the map, false otherwise
     *
     * O(N) operation
     *
     * @param TValue $value
     * @psalm-param TValue $value
     * @return bool
     */
    public function hasValue($value): bool;

    /**
     * Returns true if the map is empty, false otherwise
     *
     * O(1) operation
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Returns true if the map's keys type is $type, false otherwise
     *
     * @param string $type
     * @return bool
     */
    public function isKeyOf(string $type): bool;

    /**
     * Returns true if the map's values type is $type, false otherwise
     *
     * @param string $type
     * @return bool
     */
    public function isValueOf(string $type): bool;

    /**
     * Return true, if the map's keys type and values type are of given types, false otherwise
     *
     * @param string $keyType
     * @param string $valueType
     * @return bool
     */
    public function isOf(string $keyType, string $valueType): bool;

    /**
     * Returns the key of the given value. If multiple values exist in the map, the first found one will be returned
     *
     * O(N) operation
     *
     * @param TValue $value
     * @psalm-param TValue $value
     * @return TKey
     * @psalm-return TKey
     * @throws OutOfBoundsException
     */
    public function keyOf($value);

    /**
     * Returns an array of map's keys
     *
     * O(N) operation
     *
     * @return TKey[]
     */
    public function keys(): array;

    /**
     * Returns an array by applying callable to each key-value pair of the map.
     * Callable takes $pair as an argument
     *
     * O(N) operation
     *
     * @param callable $callable
     * @return array
     */
    public function map(callable $callable): array;

    /**
     * Pops random key-value pair off the map
     *
     * O(1) operation
     *
     * @return Pair
     * @throws FrozenException
     * @throws OutOfBoundsException
     */
    public function pop(): Pair;

    /**
     * Reduce the map to a single value.
     * Callable takes $carry and $pair as arguments and must return the next $carry
     *
     * O(N) operation
     *
     * @param callable $callable
     * @param mixed $initial
     * @return mixed
     */
    public function reduce(callable $callable, $initial);

    /**
     * Removes the pair by the given key from the map. Returns true, if the key existed in the map, false otherwise
     *
     * O(1) operation
     *
     * @param TKey $key
     * @psalm-param TKey $key
     * @return bool
     * @throws FrozenException
     */
    public function remove($key): bool;

    /**
     * Removes the pair by the given value from the map. Returns true, if the value existed in the map, false otherwise
     *
     * O(N) operation
     *
     * @param TValue $value
     * @psalm-param TValue $value
     * @return bool
     * @throws FrozenException
     */
    public function removeValue($value): bool;

    /**
     * Adds the key-value pair to the map. If the key existed in the map, it is overwritten
     *
     * O(1) operation
     *
     * @param TKey $key
     * @psalm-param TKey $key
     * @param $value
     * @psalm-param TValue $value
     * @return void
     */
    public function set($key, $value): void;

    /**
     * Returns an array of key-value pairs of the map
     *
     * O(N) operation
     *
     * @return Pair[]
     */
    public function toArray(): array;

    /**
     * Returns an array of map's values
     *
     * O(N) operation
     *
     * @return TValue[]
     * @psalm-return TValue[]
     */
    public function values(): array;

    /**
     * Apply callable to all pairs in the map.
     * Callable takes $pair as an argument. Return value of callable isn't used
     *
     * O(N) operation
     *
     * @param callable $callable
     * @return void
     */
    public function walk(callable $callable): void;
}
