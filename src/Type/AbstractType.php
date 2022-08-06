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
            throw new TypeException(sprintf('Variable must be of type %s, %s given', $this, $this->resolveType($value)));
        }
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function __toString()
    {
        return $this->isNullable()
            ? '?' . $this->getName()
            : $this->getName();
    }

    private function resolveType($value): string
    {
        /** @var TypeInterface[] $types */
        $types = [
            new NullType(),
            new BoolType(),
            new IntType(),
            new FloatType(),
            new StringType(),
            new IterableType(),
            new CallableType(),
            new ResourceType(),
        ];

        foreach ($types as $type) {
            if ($type->isTypeOf($value)) {
                return (string) $type;
            }
        }

        if ((new ObjectType())->isTypeOf($value)) {
            return get_class($value);
        }

        return gettype($value);
    }
}