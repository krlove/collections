<?php

declare(strict_types=1);

namespace Krlove\Collections\Iterator;

use Iterator;
use Krlove\Collections\Map\Pair;
use function current;
use function key;
use function reset;

class ScalarKeyMapIterator implements Iterator
{
    private array $array;
    private int $position = 0;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        return new Pair(key($this->array), current($this->array));
    }

    #[\ReturnTypeWillChange]
    public function next()
    {
        next($this->array);
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
        return key($this->array) !== null;
    }

    #[\ReturnTypeWillChange]
    public function rewind()
    {
        reset($this->array);
        $this->position = 0;
    }
}
