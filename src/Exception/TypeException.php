<?php

declare(strict_types=1);

namespace Krlove\Collections\Exception;

class TypeException extends CollectionException
{
    public const CODE_NULLABLE_KEY_NOT_ALLOWED = 1;
    public const CODE_KEY_TYPE_NOT_SUPPORTED = 2;
}