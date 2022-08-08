<?php

declare(strict_types=1);

namespace Krlove\Collections\Set;

use Countable;
use IteratorAggregate;
use Krlove\Collections\Freezable\FreezableInterface;
use Krlove\Collections\Type\TypeInterface;

interface SetInterface extends Countable, FreezableInterface, IteratorAggregate
{
    public function add($member): void;

    public function addMultiple($members): void;

    public function clear(): void;

    public function contains($member): bool;

    public function copy(): self;

    public function difference(SetInterface $set): SetInterface;

    public function getType(): TypeInterface;

    public function hasIntersectionWith(SetInterface $set): bool;

    public function intersection(SetInterface $set): SetInterface;

    public function isEmpty(): bool;

    public function isOf(string $type): bool;

    public function isSubsetOf(SetInterface $set): bool;

    public function pop();

    public function remove($member): bool;

    public function toArray(): array;

    public function union(SetInterface $set): SetInterface;
}
