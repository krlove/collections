<?php

declare(strict_types=1);

namespace Krlove\Collections\Freezable;

interface FreezableInterface
{
    /**
     * Freeze the collection. Frozen collections cannot be changed
     *
     * @return void
     */
    public function freeze(): void;

    /**
     * Checks whether collection is frozen
     *
     * @return bool
     */
    public function isFrozen(): bool;
}
