<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

use function is_float;
use function is_null;

class FloatType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && is_null($value)) {
            return true;
        }

        return is_float($value);
    }

    public function getName(): string
    {
        return 'float';
    }
}