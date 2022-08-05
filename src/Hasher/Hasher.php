<?php

declare(strict_types=1);

namespace Krlove\Collection\Hasher;

use Krlove\Collection\Exception\TypeException;
use Krlove\Collection\Type\ArrayType;
use Krlove\Collection\Type\BoolType;
use Krlove\Collection\Type\FloatType;
use Krlove\Collection\Type\IntType;
use Krlove\Collection\Type\NullType;
use Krlove\Collection\Type\ObjectType;
use Krlove\Collection\Type\ResourceType;
use Krlove\Collection\Type\StringType;
use Krlove\Collection\Type\TypeInterface;

class Hasher
{
    public static function hash($value): string
    {
        $type = self::inferType($value);
        $hashingMethod = 'hash' . ucfirst($type);

        return self::$hashingMethod($value);
    }

    private static function inferType($value): string
    {
        /** @var TypeInterface[] $types */
        $typeClasses = [
            NullType::class,
            BoolType::class,
            IntType::class,
            FloatType::class,
            StringType::class,
            ArrayType::class,
            ResourceType::class,
            ObjectType::class,
        ];

        foreach ($typeClasses as $typeClass) {
            $type = new $typeClass();
            if ($type->isTypeOf($value)) {
                return (string) $type;
            }
        }

        throw new TypeException(sprintf('Hashing of type %s is not supported', gettype($value)));
    }

    private static function hashNull(): string
    {
        return 'n';
    }

    private static function hashBool(bool $value): string
    {
        return 'b' . ($value ? '1' : '0');
    }

    private static function hashInt(int $value): string
    {
        return 'i' . $value;
    }

    private static function hashFloat(float $value): string
    {
        return 'f' . floatval($value);
    }

    private static function hashString(string $value): string
    {
        return 's' . md5($value);
    }

    private static function hashArray(array $value): string
    {
        ksort($value);
        $hashArray = [];
        foreach ($value as $key => $item) {
            $hashArray[$key] = self::hash($item);
        }

        return 'a' . md5(serialize($hashArray));
    }

    private static function hashResource($value): string
    {
        return 'r' . $value;
    }

    private static function hashObject(object $value): string
    {
        return 'o' . \spl_object_hash($value);
    }
}
