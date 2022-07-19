<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use Krlove\Collection\Type\ObjectType;
use Krlove\Collection\Type\TypeInterface;

class ObjectKeyMap extends AbstractObjectKeyMap
{
    public function __construct(TypeInterface $valueType)
    {
        parent::__construct($valueType);

        $this->keyType = new ObjectType();
    }
}