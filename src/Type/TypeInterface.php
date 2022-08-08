<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

interface TypeInterface
{
    public function assertIsTypeOf($value): void;

    public function getName(): string;

    public function isNullable(): bool;

    public function isTypeOf($value): bool;

    public function __toString();
}