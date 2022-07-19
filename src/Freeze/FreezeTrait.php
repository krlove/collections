<?php

declare(strict_types=1);

namespace Krlove\Collection\Freeze;

use Krlove\Collection\Exception\FrozenException;

trait FreezeTrait
{
    private bool $frozen = false;

    public function freeze(): void
    {
        $this->frozen = true;
    }

    public function isFrozen(): bool
    {
        return $this->frozen;
    }

    protected function assertNotFrozen(): void
    {
        if ($this->isFrozen()) {
            throw new FrozenException(sprintf('%s is frozen and can not be changed', get_class($this)));
        }
    }
}