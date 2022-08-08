<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

class TypeFactory
{
    public static function create(string $type): TypeInterface
    {
        if ($type[0] === '?') {
            $isNullable = true;
            $type = \substr($type, 1);
        } else {
            $isNullable = false;
        }
        
        switch ($type) {
            case 'mixed':
                return new MixedType($isNullable);
            case 'bool':
                return new BoolType($isNullable);
            case 'int':
                return new IntType($isNullable);
            case 'float':
                return new FloatType($isNullable);
            case 'string':
                return new StringType($isNullable);
            case 'array':
                return new ArrayType($isNullable);
            case 'object':
                return new ObjectType($isNullable);
            case 'resource':
                return new ResourceType($isNullable);
            case 'callable':
                return new CallableType($isNullable);
            case 'iterable':
                return new IterableType($isNullable);
            case 'null':
                return new NullType($isNullable);
            default:
                return new ClassType($type, $isNullable);
        }
    }
}