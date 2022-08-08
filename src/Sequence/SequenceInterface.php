<?php

declare(strict_types=1);

namespace Krlove\Collections\Sequence;

use Countable;
use IteratorAggregate;
use Krlove\Collections\Freezable\FreezableInterface;
use Krlove\Collections\Type\TypeInterface;

interface SequenceInterface extends Countable, FreezableInterface, IteratorAggregate
{
    public function clear(): void;

    public function copy(): self;

    public function first();

    public function get(int $index);

    public function getType(): TypeInterface;

    public function has(int $index): bool;

    public function hasEntry($entry): bool;

    public function indexOf($entry): int;

    public function insert(int $index, $entry): void;

    public function isEmpty(): bool;

    public function isOf(string $type): bool;

    public function last();

    public function pop();

    public function push($entry): void;

    public function pushMultiple(array $entries): void;

    public function remove(int $index): bool;

    public function removeEntry($entry): bool;

    public function shift();

    public function toArray(): array;

    public function unshift($entry): void;
}
