<?php

declare(strict_types=1);

namespace Krlove\Collection\Type;

class CallableType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_callable($value, true);
    }

    public function __toString()
    {
        return 'callable';
    }
}