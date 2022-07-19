<?php

namespace Krlove\Collection\Map;

use Krlove\Collection\Type\StringType;
use Krlove\Collection\Type\TypeInterface;

class StringScalarKeyMap extends AbstractScalarKeyMap
{
    public function __construct(TypeInterface $valueType)
    {
        parent::__construct($valueType);

        $this->keyType = new StringType();
    }
}