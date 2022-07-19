<?php

namespace Krlove\Collection\Type;

class TypeFactory
{
    public static function create(string $type): TypeInterface
    {
        switch ($type) {
            case 'mixed':
                return new MixedType();
            case 'bool':
                return new BoolType();
            case 'int':
                return new IntType();
            case 'float':
                return new FloatType();
            case 'string':
                return new StringType();
            case 'array':
                return new ArrayType();
            case 'object':
                return new ObjectType();
            case 'resource':
                return new ResourceType();
            case 'callable':
                return new CallableType();
            case 'iterable':
                return new IterableType();
            case 'null':
                return new NullType();
            default:
                return new ClassType($type);
        }
    }
}