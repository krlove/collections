<?php

declare(strict_types=1);

namespace Krlove\Collection\Type;

class StringType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && is_null($value)) {
            return true;
        }

        return is_string($value);
    }

    public function __toString()
    {
        return 'string';
    }
}