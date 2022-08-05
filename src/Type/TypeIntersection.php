<?php

declare(strict_types=1);

namespace Krlove\Collection\Type;

class TypeIntersection
{
    public static function between(TypeInterface $type1, TypeInterface $type2): ?string
    {
        if ((string) $type1 === (string) $type2) {
            return (string) $type1;
        }

        if ($type1->getName() === $type2->getName()) {
            return '?' . $type1->getName();
        }

        $typeNames = [$type1->getName(), $type2->getName()];
        if (in_array('mixed', $typeNames)) {
            return 'mixed';
        }

        $isAnyNullable = $type1->isNullable() || $type2->isNullable();
        if (in_array('object', $typeNames) && in_array('class', $typeNames)) {
            return $isAnyNullable ? '?object' : 'object';
        }

        if ($type1 instanceof ClassType && $type2 instanceof ClassType) {
            $class1 = $type1->getName();
            $class2 = $type2->getName();

            if (is_subclass_of($class1, $class2)) {
                $commonClass = $class2;
            } elseif (is_subclass_of($class2, $class1)) {
                $commonClass = $class1;
            }

            if (isset($commonClass)) {
                return $isAnyNullable ? '?' . $commonClass : $commonClass;
            }
        }

        if (in_array('array', $typeNames) && in_array('iterable', $typeNames)) {
            return $isAnyNullable ? '?iterable' : 'iterable';
        }

        return null;
    }
}
