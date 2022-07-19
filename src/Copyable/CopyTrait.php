<?php

declare(strict_types=1);

namespace Krlove\Collection\Copyable;

trait CopyTrait
{
    public function copy(): self
    {
        return clone $this;
    }
}
