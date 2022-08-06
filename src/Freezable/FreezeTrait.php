<?php

declare(strict_types=1);

namespace Krlove\Collections\Freezable;

use Krlove\Collections\Exception\FrozenException;

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
            $reflectionClass = new \ReflectionClass($this);

            throw new FrozenException(sprintf('%s is frozen and can not be changed', $reflectionClass->getShortName()));
        }
    }
}