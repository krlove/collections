<?php

namespace Krlove\Collection\Map;

use Krlove\Collection\Type\TypeFactory;

class MapFactory
{
    public static function create(string $keyType, string $valueType): MapInterface
    {
        $valueType = TypeFactory::create($valueType);

        switch ($keyType) {
            case 'int':
                return new IntScalarKeyMap($valueType);
            case 'float':
                return new FloatKeyMap($valueType);
            case 'string':
                return new StringScalarKeyMap($valueType);
            case 'object':
                return new ObjectKeyMap($valueType);
            default:
                $class = $keyType;

                return new ClassKeyMap($valueType, $class);
        }
    }
}