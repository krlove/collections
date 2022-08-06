<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

class MixedType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return true;
    }

    public function isNullable(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return 'mixed';
    }

    public function __toString()
    {
        return 'mixed';
    }
}
