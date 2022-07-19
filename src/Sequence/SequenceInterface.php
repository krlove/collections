<?php

declare(strict_types=1);

namespace Krlove\Collection\Sequence;

use Countable;
use IteratorAggregate;
use Krlove\Collection\Freezable\FreezableInterface;

interface SequenceInterface extends Countable, FreezableInterface, IteratorAggregate
{
    public function clear(): void;
    public function count(): int;
    public function first();
    public function get(int $index);
    public function getIterator();
    public function getType(): string;
    public function has(int $index): bool;
    public function hasEntry($entry): bool;
    public function indexOf($entry): ?int;
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
