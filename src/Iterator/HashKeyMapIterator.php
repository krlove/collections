<?php

declare(strict_types=1);

namespace Krlove\Collections\Iterator;

use Iterator;
use Krlove\Collections\Map\Pair;
use function reset;

class HashKeyMapIterator implements Iterator
{
    private array $keys;
    private array $values;
    private int $position = 0;

    public function __construct(array $keys, array $values)
    {
        $this->keys = $keys;
        $this->values = $values;
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        $key = key($this->keys);
        return new Pair($this->keys[$key], $this->values[$key]);
    }

    #[\ReturnTypeWillChange]
    public function next()
    {
        next($this->keys);
        $this->position++;
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->position;
    }

    #[\ReturnTypeWillChange]
    public function valid()
    {
        return key($this->keys) !== null;
    }

    #[\ReturnTypeWillChange]
    public function rewind()
    {
        reset($this->keys);
        $this->position = 0;
    }
}
