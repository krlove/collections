<?php

declare(strict_types=1);

namespace Krlove\Collection\Map;

use Krlove\Collection\Type\TypeFactory;

class MapFactory
{
    public static function create(string $keyType, string $valueType): MapInterface
    {
        $keyType = TypeFactory::create($keyType);
        $valueType = TypeFactory::create($valueType);

        return in_array($keyType, ['int', 'string']) && !$keyType->isNullable()
            ? new ScalarKeyMap($keyType, $valueType)
            : new HashKeyMap($keyType, $valueType);
    }
}
