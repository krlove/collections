<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

class NullType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_null($value);
    }

    public function isNullable(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return 'null';
    }

    public function __toString()
    {
        return 'null';
    }
}
