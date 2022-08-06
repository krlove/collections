<?php

declare(strict_types=1);

namespace Tests\Krlove\Map;

use ArrayIterator;
use Exception;
use Krlove\Collections\Exception\FrozenException;
use Krlove\Collections\Exception\OutOfBoundsException;
use Krlove\Collections\Iterator\MapIterator;
use Krlove\Collections\Map\Map;
use Krlove\Collections\Map\Pair;
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

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testFreeze(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        $map->freeze();
        self::assertTrue($map->isFrozen());

        $thrownExceptions = [];

        try {
            $map->clear();
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $map->pop();
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $map->remove($key1);
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $map->removeValue($value1);
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $map->set($key1, $value1);
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }

        self::assertCount(5, $thrownExceptions);
        foreach ($thrownExceptions as $exception) {
            self::assertInstanceOf(FrozenException::class, $exception);
            self::assertEquals('Map is frozen and can not be changed', $exception->getMessage());
        }
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testGet(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        self::assertEquals($value1, $map->get($key1));
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testGetWrongKey(string $keyType, string $valueType, $key1): void
    {
        self::expectException(OutOfBoundsException::class);

        $map = Map::of($keyType, $valueType);
        $map->get($key1);
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testGetIterator(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        foreach ($map as $key => $value) {
            self::assertEquals($key1, $key);
            self::assertEquals($value1, $value);
        }

        if ($keyType === 'int' || $keyType === 'string') {
            self::assertInstanceOf(ArrayIterator::class, $map->getIterator());
        } else {
            self::assertInstanceOf(MapIterator::class, $map->getIterator());
        }
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testGetKeyType(string $keyType, string $valueType): void
    {
        $map = Map::of($keyType, $valueType);
        self::assertEquals($keyType, $map->getKeyType());
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testGetValueType(string $keyType, string $valueType): void
    {
        $map = Map::of($keyType, $valueType);
        self::assertEquals($valueType, $map->getValueType());
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testHas(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        self::assertFalse($map->has($key1));
        $map->set($key1, $value1);
        self::assertTrue($map->has($key1));
        $map->remove($key1);
        self::assertFalse($map->has($key1));
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testHasValue(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        self::assertFalse($map->hasValue($value1));
        $map->set($key1, $value1);
        self::assertTrue($map->hasValue($value1));
        $map->remove($key1);
        self::assertFalse($map->hasValue($value1));
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testIsEmpty(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        self::assertTrue($map->isEmpty());
        $map->set($key1, $value1);
        self::assertFalse($map->isEmpty());
        $map->remove($key1);
        self::assertTrue($map->isEmpty());
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testIsKeyOf(string $keyType, string $valueType): void
    {
        $map = Map::of($keyType, $valueType);
        self::assertTrue($map->isKeyOf($keyType));
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testIsValueOf(string $keyType, string $valueType): void
    {
        $map = Map::of($keyType, $valueType);
        self::assertTrue($map->isValueOf($valueType));
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testIsOf(string $keyType, string $valueType): void
    {
        $map = Map::of($keyType, $valueType);
        self::assertTrue($map->isOf($keyType, $valueType));
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testKeyOf(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        self::assertEquals($key1, $map->keyOf($value1));
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testKeyOfWrongValue(string $keyType, string $valueType, $key1, $value1): void
    {
        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Value not found in the Map');

        $map = Map::of($keyType, $valueType);
        $map->keyOf($value1);
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testKeys(string $keyType, string $valueType, $key1, $value1, $key2, $value2): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        $map->set($key2, $value2);

        $keys = $map->keys();
        if ($keyType === 'null') {
            self::assertCount(1, $keys);
            self::assertContains($key1, $keys);

            return;
        }

        self::assertCount(2, $keys);
        self::assertContains($key1, $keys);
        self::assertContains($key2, $keys);
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testPop(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        $pair = $map->pop();
        self::assertInstanceOf(Pair::class, $pair);
        self::assertEquals($key1, $pair->getKey());
        self::assertEquals($value1, $pair->getValue());
        self::assertTrue($map->isEmpty());
    }

    public function testPopEmptySet(): void
    {
        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Can not pop from an empty Map');

        $map = Map::of('int', 'string');
        $map->pop();
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testRemove(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        self::assertTrue($map->has($key1));
        self::assertTrue($map->hasValue($value1));
        self::assertTrue($map->remove($key1));
        self::assertFalse($map->remove($key1));
        self::assertFalse($map->has($key1));
        self::assertFalse($map->hasValue($value1));
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testRemoveValue(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        self::assertTrue($map->has($key1));
        self::assertTrue($map->hasValue($value1));
        self::assertTrue($map->removeValue($value1));
        self::assertFalse($map->removeValue($value1));
        self::assertFalse($map->has($key1));
        self::assertFalse($map->hasValue($value1));
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testSet(string $keyType, string $valueType, $key1, $value1, $key2, $value2): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        $map->set($key2, $value2);

        if ($keyType === 'null') {
            self::assertCount(1, $map);
            self::assertEquals($value2, $map->get($key1));
            self::assertEquals($value2, $map->get($key2));

            return;
        }

        self::assertCount(2, $map);
        self::assertEquals($value1, $map->get($key1));
        self::assertEquals($value2, $map->get($key2));
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testToArray(string $keyType, string $valueType, $key1, $value1): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        $pairs = $map->toArray();
        foreach ($pairs as $pair) {
            self::assertInstanceOf(Pair::class, $pair);
            self::assertEquals($key1, $pair->getKey());
            self::assertEquals($value1, $pair->getValue());
        }
    }

    /**
     * @dataProvider keyValueTypesDataProvider
     */
    public function testValues(string $keyType, string $valueType, $key1, $value1, $key2, $value2): void
    {
        $map = Map::of($keyType, $valueType);
        $map->set($key1, $value1);
        $map->set($key2, $value2);

        $values = $map->values();
        if ($keyType === 'null') {
            self::assertCount(1, $values);
            self::assertContains($value2, $values);

            return;
        }

        self::assertCount(2, $values);
        self::assertContains($value1, $values);
        self::assertContains($value2, $values);
    }
}
