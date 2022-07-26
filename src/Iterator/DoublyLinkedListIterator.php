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

    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->list->current();
    }

    #[\ReturnTypeWillChange]
    public function next()
    {
        $this->list->next();
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->list->key();
    }

    #[\ReturnTypeWillChange]
    public function valid()
    {
        return $this->list->valid();
    }

    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->list->rewind();
    }
}
