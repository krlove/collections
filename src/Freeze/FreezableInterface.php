<?php

declare(strict_types=1);

namespace Krlove\Collection\Freeze;

interface FreezableInterface
{
    public function freeze(): void;
    public function isFrozen(): bool;
}
