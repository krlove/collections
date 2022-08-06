<?php

declare(strict_types=1);

namespace Krlove\Collections\Freezable;

interface FreezableInterface
{
    public function freeze(): void;
    public function isFrozen(): bool;
}
