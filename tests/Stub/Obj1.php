<?php

declare(strict_types=1);

namespace Tests\Krlove\Stub;

class Obj1
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}