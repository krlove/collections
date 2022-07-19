<?php

namespace Krlove\Collection\Type;

class ResourceType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_resource($value);
    }

    public function __toString()
    {
        return 'resource';
    }
}