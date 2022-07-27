<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use Krlove\Collection\Exception\TypeException;
use Krlove\Collection\Type\TypeFactory;

class MapFactory
{
    public static function create(string $keyType, string $valueType): MapInterface
    {
        $valueType = TypeFactory::create($valueType);

        self::assertKeyTypeAllowed($keyType);

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

                // todo can I stick to ObjectType only?
                return new ClassKeyMap($valueType, $class);
        }
    }

    private static function assertKeyTypeAllowed(string $type): void
    {
        if ($type[0] === '?') {
            throw new TypeException(
                'Nullable types are not allowed as a Map keys type',
                TypeException::CODE_NULLABLE_KEY_NOT_ALLOWED
            );
        }

        if (in_array($type, ['null', 'bool', 'iterable', 'callable', 'resource', 'mixed'])) {
            throw new TypeException(
                sprintf('Type %s is not supported as a Map keys type', $type),
                TypeException::CODE_KEY_TYPE_NOT_SUPPORTED
            );
        }
    }
}