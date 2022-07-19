<?php

namespace Krlove\Collection\Type;

interface TypeInterface
{
    public function isTypeOf($value): bool;
    public function assertIsTypeOf($value): void;
    public function __toString();
}