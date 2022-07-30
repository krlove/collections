<?php

declare(strict_types=1);

namespace Krlove\Collection\Type;

class MixedType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return true;
    }

    public function getType(): string
    {
        return 'mixed';
    }
}
