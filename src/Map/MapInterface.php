<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use Countable;
use IteratorAggregate;
use Krlove\Collection\Copyable\CopyableInterface;
use Krlove\Collection\Freezable\FreezableInterface;

interface MapInterface extends CopyableInterface, Countable, FreezableInterface, IteratorAggregate
{
    public function isOf(string $keyType, string $valueType): bool;
    public function isKeyOf(string $type): bool;
    public function isValueOf(string $type): bool;
    public function set($key, $value): void;
    public function setMultiple(array $array): void;
    public function get($key);
    public function getKeyType(): string;
    public function getValueType(): string;
    public function has($key): bool;
    public function hasValue($value): bool;
    public function remove($key): bool;
    public function removeValue($value): bool;
    public function keyOf($value);
    public function toArray(): array;
    public function keys(): array;
    public function values(): array;
    public function clear(): void;
    public function isEmpty(): bool;
    public function count(): int;
    public function getIterator();
}
