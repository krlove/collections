<?php

namespace Krlove\Collection\Sequence;

use ArrayIterator;
use Krlove\Collection\Exception\OutOfBoundsException;
use Krlove\Collection\Freeze\FreezeTrait;
use Krlove\Collection\Type\TypeFactory;
use Krlove\Collection\Type\TypeInterface;

use function array_key_exists;
use function array_search;
use function count;
use function end;
use function in_array;
use function reset;

class Sequence implements SequenceInterface
{
    use FreezeTrait;

    private array $entries = [];
    private TypeInterface $type;

    private function __construct(TypeInterface $type)
    {
        $this->type = $type;
    }

    public static function of(string $type): self
    {
        return new self(TypeFactory::create($type));
    }

    public function isOf(string $type): bool
    {
        return $type === $this->getType();
    }

    public function add($entry): void
    {
        $this->assertNotFrozen();

        $this->type->assertIsTypeOf($entry);

        $this->entries[] = $entry;
    }

    public function addMultiple(array $entries): void
    {
        $this->assertNotFrozen();

        foreach ($entries as $entry) {
            $this->add($entry);
        }
    }

    public function get(int $index)
    {
        if (!$this->has($index)) {
            throw new OutOfBoundsException(sprintf('Index %d does not exist', $index));
        }

        return $this->entries[$index];
    }

    public function getType(): string
    {
        return (string) $this->type;
    }

    public function has(int $index): bool
    {
        return array_key_exists($index, $this->entries);
    }

    public function hasEntry($entry): bool
    {
        return in_array($entry, $this->entries, true);
    }

    public function first()
    {
        return reset($this->entries);
    }

    public function last()
    {
        return end($this->entries);
    }

    public function toArray(): array
    {
        return $this->entries;
    }

    public function indexOf($entry): ?int
    {
        $index = array_search($entry, $this->entries, true);

        if ($index === false) {
            return null;
        }

        return (int) $index;
    }

    public function remove(int $index): bool
    {
        $this->assertNotFrozen();

        if ($this->has($index)) {
            unset($this->entries[$index]);

            return true;
        }

        return false;
    }

    public function removeEntry($entry): bool
    {
        $this->assertNotFrozen();

        $index = $this->indexOf($entry);

        if ($index !== false) {
            $this->remove($index);

            return true;
        }

        return false;
    }

    public function clear(): void
    {
        $this->assertNotFrozen();

        $this->entries = [];
    }

    public function isEmpty(): bool
    {
        return empty($this->entries);
    }

    public function count(): int
    {
        return count($this->entries);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->entries);
    }

    public function pop()
    {
        $this->assertNotFrozen();

        return array_pop($this->entries);
    }

    public function push($entry): void
    {
        $this->assertNotFrozen();

        $this->type->assertIsTypeOf($entry);

        array_push($this->entries, $entry);
    }

    public function shift()
    {
        $this->assertNotFrozen();

        return array_shift($this->entries);
    }

    public function unshift($entry): void
    {
        $this->assertNotFrozen();

        $this->type->assertIsTypeOf($entry);

        array_unshift($this->entries, $entry);
    }
}
