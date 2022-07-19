<?php

declare(strict_types=1);

namespace Krlove\Collection\Type;

interface TypeInterface
{
    public function isTypeOf($value): bool;
    public function assertIsTypeOf($value): void;
    public function __toString();
}