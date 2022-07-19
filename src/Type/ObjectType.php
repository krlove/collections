<?php

declare(strict_types=1);

namespace Krlove\Collection\Type;

class ObjectType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_object($value);
    }

    public function __toString()
    {
        return 'object';
    }
}