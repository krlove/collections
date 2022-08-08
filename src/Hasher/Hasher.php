<?php

declare(strict_types=1);

namespace Krlove\Collections\Hasher;

use Krlove\Collections\Exception\TypeException;
use Krlove\Collections\Type\ArrayType;
use Krlove\Collections\Type\BoolType;
use Krlove\Collections\Type\FloatType;
use Krlove\Collections\Type\IntType;
use Krlove\Collections\Type\NullType;
use Krlove\Collections\Type\ObjectType;
use Krlove\Collections\Type\ResourceType;
use Krlove\Collections\Type\StringType;
use Krlove\Collections\Type\TypeInterface;

class Hasher
{
    public static function hash($value): string
    {
        $type = self::inferType($value);
        $hashingMethod = 'hash' . \ucfirst($type);

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

        throw new TypeException(sprintf('Hashing of type %s is not supported', \gettype($value)));
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
        return 'f' . $value;
    }

    private static function hashString(string $value): string
    {
        return 's' . \md5($value);
    }

    private static function hashArray(array $value): string
    {
        \ksort($value);
        $hashArray = [];
        foreach ($value as $key => $item) {
            $hashArray[$key] = self::hash($item);
        }

        return 'a' . \md5(\serialize($hashArray));
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
