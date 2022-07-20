<?php

declare(strict_types=1);

namespace Krlove\Collection\Type;

use Krlove\Collection\Exception\TypeException;

abstract class AbstractType implements TypeInterface
{
    protected bool $nullable = false;

    public function __construct(bool $nullable = false)
    {
        $this->nullable = $nullable;
    }

    public function assertIsTypeOf($value): void
    {
        // todo include nullable in exception message
        if (!$this->isTypeOf($value)) {
            throw new TypeException(sprintf('Variable must be of type %s', $this));
        }
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}