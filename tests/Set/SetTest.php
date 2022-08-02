<?php

declare(strict_types=1);

namespace Set;

use Krlove\Collection\Set\Set;
use PHPUnit\Framework\TestCase;
use Tests\Krlove\TypesProviderTrait;

class SetTest extends TestCase
{
    use TypesProviderTrait;

    /**
     * @dataProvider typesDataProvider
     */
    public function testOf(string $type): void
    {
        $set = Set::of($type);
        self::assertEquals($type, (string) $set->getType());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testAdd(string $type, $value1, $value2, $value3): void
    {
        $set = Set::of($type);
        $set->add($value1);
        $set->add($value2);
        $set->add($value3);
        self::assertFalse($set->isEmpty());
        self::assertTrue($set->has($value1));
        self::assertTrue($set->has($value2));
        self::assertTrue($set->has($value3));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testAddMultiple(string $type, $value1, $value2, $value3): void
    {
        $set = Set::of($type);
        $set->addMultiple([$value1, $value2, $value3]);
        self::assertFalse($set->isEmpty());
        self::assertTrue($set->has($value1));
        self::assertTrue($set->has($value2));
        self::assertTrue($set->has($value3));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testClear(string $type, $value1): void
    {
        $set = Set::of($type);
        $set->add($value1);
        self::assertCount(1, $set);
        $set->clear();
        self::assertCount(0, $set);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testCopy(string $type, $value1): void
    {
        $set = Set::of($type);
        $set->add($value1);

        $copy = $set->copy();
        self::assertNotSame($set, $copy);
        self::assertCount(1, $copy);
        self::assertSame($value1, $copy->pop());
    }
}
