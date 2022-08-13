<?php

declare(strict_types=1);

namespace Krlove\Collections\Freezable;

use Krlove\Collections\Exception\FrozenException;
use ReflectionClass;
use function sprintf;

trait FreezeTrait
{
    private bool $frozen = false;

    /**
     * {@inheritDoc}
     */
    public function freeze(): void
    {
        $this->frozen = true;
    }

    /**
     * {@inheritDoc}
     */
    public function isFrozen(): bool
    {
        return $this->frozen;
    }

    /**
     * @return void
     * @throws FrozenException
     */
    protected function assertNotFrozen(): void
    {
        if ($this->isFrozen()) {
            $reflectionClass = new ReflectionClass($this);

            throw new FrozenException(sprintf('%s is frozen and can not be changed', $reflectionClass->getShortName()));
        }
    }
}