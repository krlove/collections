<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

use function is_callable;
use function is_null;

class CallableType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && is_null($value)) {
            return true;
        }

        return is_callable($value, true);
    }

    public function getName(): string
    {
        return 'callable';
    }
}