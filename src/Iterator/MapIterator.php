<?php

declare(strict_types=1);

namespace Krlove\Collection\Iterator;

use Iterator;

class MapIterator implements Iterator
{
    private array $keys;
    private array $values;

    public function __construct(array $keys, array $values)
    {
        $this->keys = $keys;
        $this->values = $values;
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        return current($this->values);
    }

    #[\ReturnTypeWillChange]
    public function next()
    {
        next($this->keys);

        return next($this->values);
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return current($this->keys);
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
        reset($this->values);
    }
}
