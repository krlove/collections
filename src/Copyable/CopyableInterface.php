<?php

declare(strict_types=1);

namespace Krlove\Collection\Copyable;

interface CopyableInterface
{
    public function copy(): self;
}
