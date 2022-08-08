<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

class ResourceType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && \is_null($value)) {
            return true;
        }

        return \is_resource($value) || ($value !== null && !\is_scalar($value) && !\is_array($value) && !\is_object($value));
    }

    public function getName(): string
    {
        return 'resource';
    }
}
