<?php

declare(strict_types=1);

namespace Krlove\Collection\Iterator;

use Iterator;
use SplDoublyLinkedList;

class DoublyLinkedListIterator implements Iterator
{
    private SplDoublyLinkedList $list;

    public function __construct(SplDoublyLinkedList $list)
    {
        $this->list = $list;
    }

    public function current()
    {
        return $this->list->current();
    }

    public function next()
    {
        $this->list->next();
    }

    public function key()
    {
        return $this->list->key();
    }

    public function valid()
    {
        return $this->list->valid();
    }

    public function rewind()
    {
        $this->list->rewind();
    }
}
