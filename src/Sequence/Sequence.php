<?php

declare(strict_types=1);

namespace Krlove\Collections\Sequence;

use Krlove\Collections\Exception\OutOfBoundsException;
use Krlove\Collections\Freezable\FreezeTrait;
use Krlove\Collections\Iterator\SequenceIterator;
use Krlove\Collections\Set\Set;
use Krlove\Collections\Type\TypeFactory;
use Krlove\Collections\Type\TypeInterface;
use SplDoublyLinkedList;
use function call_user_func;
use function iterator_to_array;
use function sprintf;
use function usort;

/**
 * @psalm-template T
 * @template-implements SequenceInterface<T>
 */
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

    /**
     * {@inheritDoc}
     */
    public function clear(): void
    {
        $this->assertNotFrozen();

        $this->list = new SplDoublyLinkedList();
    }

    /**
     * {@inheritDoc}
     */
    public function copy(): self
    {
        $sequence = Sequence::of((string)$this->getType());

        foreach ($this as $entry) {
            $sequence->push($entry);
        }

        return $sequence;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return $this->list->count();
    }

    /**
     * {@inheritDoc}
     */
    public function filter(callable $callable): SequenceInterface
    {
        $filtered = Sequence::of((string) $this->getType());
        foreach ($this->list as $index => $entry) {
            if (call_user_func($callable, $entry, $index) === true) {
                $filtered->push($entry);
            }
        }

        return $filtered;
    }

    /**
     * {@inheritDoc}
     */
    public function first()
    {
        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Unable to retrieve the first entry - sequence is empty');
        }

        return $this->get(0);
    }

    /**
     * {@inheritDoc}
     */
    public function get(int $index)
    {
        if (!$this->has($index)) {
            throw new OutOfBoundsException(sprintf('Index %d is out of bounds', $index));
        }

        return $this->list[$index];
    }

    /**
     * {@inheritDoc}
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new SequenceIterator($this->list);
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function has(int $index): bool
    {
        return $index >= 0 && $index < $this->list->count();
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function insert(int $index, $entry): void
    {
        $this->assertNotFrozen();

        $this->type->assertIsTypeOf($entry);

        if ($index < 0 || $index > $this->count()) {
            throw new OutOfBoundsException(sprintf('Index %d is out of bounds', $index));
        }

        $this->list->add($index, $entry);
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * {@inheritDoc}
     */
    public function isOf(string $type): bool
    {
        return $type === (string)$this->getType();
    }

    /**
     * {@inheritDoc}
     */
    public function last()
    {
        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Unable to retrieve the last entry - sequence is empty');
        }

        return $this->list[$this->count() - 1];
    }

    /**
     * {@inheritDoc}
     */
    public function map(callable $callable): array
    {
        $result = [];
        foreach ($this->list as $index => $entry) {
            $result[] = call_user_func($callable, $entry, $index);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function pop()
    {
        $this->assertNotFrozen();

        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Unable to pop an entry - sequence is empty');
        }

        return $this->list->pop();
    }

    /**
     * {@inheritDoc}
     */
    public function push($entry): void
    {
        $this->assertNotFrozen();

        $this->type->assertIsTypeOf($entry);

        $this->list->push($entry);
    }

    /**
     * {@inheritDoc}
     */
    public function pushMultiple(array $entries): void
    {
        $this->assertNotFrozen();

        foreach ($entries as $entry) {
            $this->push($entry);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function reduce(callable $callable, $initial)
    {
        $carry = $initial;
        foreach ($this->list as $index => $entry) {
            $carry = call_user_func($callable, $carry, $entry, $index);
        }

        return $carry;
    }

    /**
     * {@inheritDoc}
     */
    public function remove(int $index): bool
    {
        $this->assertNotFrozen();

        if (!$this->has($index)) {
            return false;
        }

        $this->list->offsetUnset($index);

        return true;
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function shift()
    {
        $this->assertNotFrozen();

        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Unable to shift an entry - sequence is empty');
        }

        return $this->list->shift();
    }

    /**
     * {@inheritDoc}
     */
    public function sort(callable $callable): void
    {
        $this->assertNotFrozen();

        $entries = $this->toArray();
        usort($entries, $callable);

        $this->clear();
        $this->pushMultiple($entries);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }

    /**
     * {@inheritDoc}
     */
    public function unique(): SequenceInterface
    {
        $set = Set::of((string) $this->type);
        foreach ($this->list as $entry) {
            $set->add($entry);
        }

        $unique = Sequence::of((string) $this->type);
        $unique->pushMultiple($set->toArray());

        return $unique;
    }

    /**
     * {@inheritDoc}
     */
    public function unshift($entry): void
    {
        $this->assertNotFrozen();

        $this->type->assertIsTypeOf($entry);

        $this->list->unshift($entry);
    }

    /**
     * {@inheritDoc}
     */
    public function walk(callable $callable): void
    {
        foreach ($this->list as $index => $entry) {
            call_user_func($callable, $entry, $index);
        }
    }
}
