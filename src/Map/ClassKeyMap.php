<?php

namespace Krlove\Collection\Map;

use Krlove\Collection\Type\ClassType;
use Krlove\Collection\Type\TypeInterface;

class ClassKeyMap extends AbstractObjectKeyMap
{
    public function __construct(TypeInterface $valueType, string $class)
    {
        parent::__construct($valueType);

        $this->keyType = new ClassType($class);
    }
}