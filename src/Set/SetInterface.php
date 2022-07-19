<?php

namespace Krlove\Collection\Set;

use Countable;
use IteratorAggregate;
use Krlove\Collection\Freeze\FreezableInterface;

interface SetInterface extends Countable, FreezableInterface, IteratorAggregate
{
    public function add($member): void;
    public function addMultiple($members): void;
    public function clear(): void;
    public function difference(SetInterface $set): ?SetInterface;
    public function getType(): string;
    public function has($member): bool;
    public function hasIntersectionWith(SetInterface $set): bool;
    public function intersection(SetInterface $set): SetInterface;
    public function isEmpty(): bool;
    public function isOf(string $type): bool;
    public function isSubsetOf(SetInterface $set): bool;
    public function remove($member): bool;
    public function toArray(): array;
    public function union(SetInterface $set): SetInterface;
}
