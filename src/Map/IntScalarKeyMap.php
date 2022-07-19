<?php

namespace Krlove\Collection\Map;

use Krlove\Collection\Type\IntType;
use Krlove\Collection\Type\TypeInterface;

class IntScalarKeyMap extends AbstractScalarKeyMap
{
    public function __construct(TypeInterface $valueType)
    {
        parent::__construct($valueType);

        $this->keyType = new IntType();
    }
}