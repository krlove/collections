<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

use function is_null;
use function is_object;

class ObjectType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && is_null($value)) {
            return true;
        }

        return is_object($value);
    }

    public function getName(): string
    {
        return 'object';
    }
}