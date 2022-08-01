<?php

declare(strict_types=1);

namespace Tests\Krlove\Map;

use Krlove\Collection\Map\Map;
use PHPUnit\Framework\TestCase;
use Tests\Krlove\TypesProviderTrait;

class MapTest extends TestCase
{
    use TypesProviderTrait;

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testOf(string $keyType, string $valueType): void
    {
        $map = Map::of($keyType, $valueType);
        self::assertEquals($keyType, $map->getKeyType());
        self::assertEquals($valueType, $map->getValueType());
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testClear(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        self::assertCount(1, $map);
        $map->clear();
        self::assertCount(0, $map);
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testCopy(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);

        $copy = $map->copy();
        self::assertNotSame($copy, $map);
        self::assertCount(1, $copy);
        self::assertSame($value1, $copy->get($key1));
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testCount(string $keyType, string $valueType, $key1, $value1, $key2, $value2): void
    {
        $map = Map::of($keyType, $valueType);
        self::assertEquals(0, $map->count());
        $map->set($key1, $value1);
        self::assertCount(1, $map);

        if ($keyType === 'null') {
            return;
        }

        $map->set($key2, $value1);
        self::assertCount(2, $map);
        $map->set($key2, $value2);
        self::assertCount(2, $map);
        $map->remove($key1);
        self::assertCount(1, $map);
        $map->remove($key2);
        self::assertCount(0, $map);
    }
}
