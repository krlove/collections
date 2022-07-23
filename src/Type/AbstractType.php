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
        if (!$this->isTypeOf($value)) {
            $message = sprintf('Variable must be of type %s', $this);
            if ($this->isNullable()) {
                $message .= ' or null';
            }

            throw new TypeException($message);
        }
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}