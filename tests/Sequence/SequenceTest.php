<?php

namespace Krlove\Tests\Sequence;

use Krlove\Collection\Sequence\Sequence;
use PHPUnit\Framework\TestCase;

class SequenceTest extends TestCase
{
    public function testAdd(): void
    {
        $sequence = Sequence::of('int');
        $sequence->add(100);

        self::assertCount(1, $sequence);
        self::assertEquals(100, $sequence->get(0));
    }

    public function testAddSameValues(): void
    {
        $sequence = Sequence::of('string');
    }
}