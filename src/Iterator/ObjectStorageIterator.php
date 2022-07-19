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

    public function current()
    {
        return $this->storage->getInfo();
    }

    public function next()
    {
        $this->storage->next();
    }

    public function key()
    {
        return $this->storage->current();
    }

    public function valid()
    {
        return $this->storage->valid();
    }

    public function rewind()
    {
        $this->storage->rewind();
    }
}
