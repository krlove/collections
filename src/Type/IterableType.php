<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

use function is_iterable;
use function is_null;

class IterableType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && is_null($value)) {
            return true;
        }

        return is_iterable($value);
    }

    public function getName(): string
    {
        return 'iterable';
    }
}