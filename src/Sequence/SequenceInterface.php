<?php

namespace Krlove\Collection\Sequence;

use Countable;
use IteratorAggregate;
use Krlove\Collection\Freeze\FreezableInterface;

interface SequenceInterface extends Countable, FreezableInterface, IteratorAggregate
{
    public function add($entry): void;
    public function addMultiple(array $entries): void;
    public function isOf(string $type): bool;
    public function get(int $index);
    public function getType(): string;
    public function has(int $index): bool;
    public function hasEntry($entry): bool;
    public function first();
    public function last();
    public function toArray(): array;
    public function indexOf($entry): ?int;
    public function remove(int $index): bool;
    public function removeEntry($entry): bool;
    public function clear(): void;
    public function isEmpty(): bool;
    public function count(): int;
    public function getIterator();
    public function pop();
    public function push($entry): void;
    public function shift();
    public function unshift($entry): void;
}
