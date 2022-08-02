<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use Countable;
use IteratorAggregate;
use Krlove\Collection\Freezable\FreezableInterface;
use Krlove\Collection\Type\TypeInterface;

interface MapInterface extends Countable, FreezableInterface, IteratorAggregate
{
    public function clear(): void;
    public function copy(): self;
    public function get($key);
    public function getKeyType(): TypeInterface;
    public function getValueType(): TypeInterface;
    public function has($key): bool;
    public function hasValue($value): bool;
    public function isEmpty(): bool;
    public function isKeyOf(string $type): bool;
    public function isValueOf(string $type): bool;
    public function isOf(string $keyType, string $valueType): bool;
    public function keyOf($value);
    public function keys(): array;
    public function pop(): Pair;
    public function remove($key): bool;
    public function removeValue($value): bool;
    public function set($key, $value): void;
    public function toArray(): array;
    public function values(): array;
}
