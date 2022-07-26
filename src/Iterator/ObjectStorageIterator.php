<?php

declare(strict_types=1);

namespace Krlove\Collection\Iterator;

use Iterator;
use SplObjectStorage;

class ObjectStorageIterator implements Iterator
{
    private SplObjectStorage $storage;

    public function __construct(SplObjectStorage $storage)
    {
        $this->storage = $storage;
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->storage->getInfo();
    }

    #[\ReturnTypeWillChange]
    public function next()
    {
        $this->storage->next();
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->storage->current();
    }

    #[\ReturnTypeWillChange]
    public function valid()
    {
        return $this->storage->valid();
    }

    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->storage->rewind();
    }
}
