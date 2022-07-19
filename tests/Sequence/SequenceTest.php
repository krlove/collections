<?php

declare(strict_types=1);

namespace Tests\Krlove\Sequence;

use Krlove\Collection\Exception\TypeException;
use Krlove\Collection\Sequence\Sequence;
use PHPUnit\Framework\TestCase;
use Tests\Krlove\Stub\Key;

class SequenceTest extends TestCase
{
    public function testOf(): void
    {
        $sequence = Sequence::of('string');
        self::assertEquals('string', $sequence->getType());

        $sequence = Sequence::of(Key::class);
        self::assertEquals(Key::class, $sequence->getType());
    }

    public function testOfWrongType(): void
    {
        self::expectException(TypeException::class);
        self::expectExceptionMessage('Class or interface UnknownClass does not exist');

        Sequence::of('UnknownClass');
    }

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
        $sequence->add('Harry');
        $sequence->add('Hermione');
        $sequence->add('Ron');
        $sequence->add('Hermione');
        $sequence->add('Ron');
        $sequence->add('Harry');

        self::assertCount(6, $sequence);
        self::assertEquals('Harry', $sequence->get(0));
        self::assertEquals('Harry', $sequence->get(5));
    }

    public function testAddWrongType(): void
    {
        $sequence = Sequence::of('float');
        $sequence->add(1);
    }
}