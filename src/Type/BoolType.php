<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

use function is_bool;
use function is_null;

class BoolType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && is_null($value)) {
            return true;
        }

        return is_bool($value);
    }

    public function getName(): string
    {
        return 'bool';
    }
}