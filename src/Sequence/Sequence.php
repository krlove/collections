<?php

declare(strict_types=1);

namespace Krlove\Collections\Sequence;

use Krlove\Collections\Exception\OutOfBoundsException;
use Krlove\Collections\Freezable\FreezeTrait;
use Krlove\Collections\Iterator\SequenceIterator;
use Krlove\Collections\Type\TypeFactory;
use Krlove\Collections\Type\TypeInterface;
use SplDoublyLinkedList;

class Sequence implements SequenceInterface
{
    use FreezeTrait;

    private SplDoublyLinkedList $list;
    private TypeInterface $type;

    private function __construct(TypeInterface $type)
    {
        $this->type = $type;
        $this->list = new SplDoublyLinkedList();
    }

    public static function of(string $type): self
    {
        return new self(TypeFactory::create($type));
    }

    public function clear(): void
    {
        $this->assertNotFrozen();

        $this->list = new SplDoublyLinkedList();
    }

    public function copy(): self
    {
        $sequence = Sequence::of((string) $this->getType());

        foreach ($this as $entry) {
            $sequence->push($entry);
        }

        return $sequence;
    }

    public function count(): int
    {
        return $this->list->count();
    }

    public function first()
    {
        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Unable to retrieve the first entry - sequence is empty');
        }

        return $this->get(0);
    }

    public function get(int $index)
    {
        if (!$this->has($index)) {
            throw new OutOfBoundsException(\sprintf('Index %d is out of bounds', $index));
        }

        return $this->list[$index];
    }

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new SequenceIterator($this->list);
    }

    public function getType(): TypeInterface
    {
        return $this->type;
    }

    public function has(int $index): bool
    {
        return $index >= 0 && $index < $this->list->count();
    }

    public function hasEntry($entry): bool
    {
        if (!$this->type->isTypeOf($entry)) {
            return false;
        }

        foreach ($this->list as $item) {
            if ($item === $entry) {
                return true;
            }
        }

        return false;
    }

    public function indexOf($entry): int
    {
        if (!$this->type->isTypeOf($entry)) {
            throw new OutOfBoundsException('Entry not found in the Sequence');
        }

        foreach ($this->list as $index => $item) {
            if ($item === $entry) {
                return $index;
            }
        }

        throw new OutOfBoundsException('Entry not found in the Sequence');
    }

    public function insert(int $index, $entry): void
    {
        $this->assertNotFrozen();

        $this->type->assertIsTypeOf($entry);

        if ($index < 0 || $index > $this->count()) {
            throw new OutOfBoundsException(\sprintf('Index %d is out of bounds', $index));
        }

        $this->list->add($index, $entry);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function isOf(string $type): bool
    {
        return $type === (string) $this->getType();
    }

    public function last()
    {
        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Unable to retrieve the last entry - sequence is empty');
        }

        return $this->list[$this->count() - 1];
    }

    public function pop()
    {
        $this->assertNotFrozen();

        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Unable to pop an entry - sequence is empty');
        }

        return $this->list->pop();
    }

    public function push($entry): void
    {
        $this->assertNotFrozen();

        $this->type->assertIsTypeOf($entry);

        $this->list->push($entry);
    }

    public function pushMultiple(array $entries): void
    {
        $this->assertNotFrozen();

        foreach ($entries as $entry) {
            $this->push($entry);
        }
    }

    public function remove(int $index): bool
    {
        $this->assertNotFrozen();

        if (!$this->has($index)) {
            return false;
        }

        $this->list->offsetUnset($index);

        return true;
    }

    public function removeEntry($entry): bool
    {
        $this->assertNotFrozen();

        try {
            $index = $this->indexOf($entry);
        } catch (OutOfBoundsException $e) {
            return false;
        }

        return $this->remove($index);
    }

    public function shift()
    {
        $this->assertNotFrozen();

        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Unable to shift an entry - sequence is empty');
        }

        return $this->list->shift();
    }

    public function toArray(): array
    {
        return \iterator_to_array($this->getIterator());
    }

    public function unshift($entry): void
    {
        $this->assertNotFrozen();

        $this->type->assertIsTypeOf($entry);

        $this->list->unshift($entry);
    }
}
