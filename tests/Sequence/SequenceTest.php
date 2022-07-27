<?php

declare(strict_types=1);

namespace Tests\Krlove\Sequence;

use Exception;
use Krlove\Collection\Exception\FrozenException;
use Krlove\Collection\Exception\OutOfBoundsException;
use Krlove\Collection\Exception\TypeException;
use Krlove\Collection\Iterator\SequenceIterator;
use Krlove\Collection\Sequence\Sequence;
use PHPUnit\Framework\TestCase;
use Tests\Krlove\TypesProviderTrait;

class SequenceTest extends TestCase
{
    use TypesProviderTrait;

    /**
     * @dataProvider typesDataProvider
     */
    public function testOf(string $type): void
    {
        $sequence = Sequence::of($type);
        self::assertEquals($type, $sequence->getType());
    }

    public function testOfWrongType(): void
    {
        self::expectException(TypeException::class);
        self::expectExceptionMessage('Class or interface UnknownClass does not exist');

        Sequence::of('UnknownClass');
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testClear(string $type, $value1): void
    {
        $sequence = Sequence::of($type);
        $sequence->push($value1);
        $sequence->clear();

        self::assertCount(0, $sequence);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testCopy(string $type, $value1): void
    {
        $sequence = Sequence::of($type);
        $sequence->push($value1);

        $copy = $sequence->copy();
        self::assertNotSame($sequence, $copy);
        self::assertCount(1, $copy);
        self::assertSame($value1, $copy->first());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testCount(string $type, $value1, $value2): void
    {
        $sequence = Sequence::of($type);
        self::assertEquals(0, $sequence->count());
        $sequence->push($value1);
        self::assertEquals(1, $sequence->count());
        $sequence->push($value2);
        self::assertEquals(2, $sequence->count());
        $sequence->remove(0);
        self::assertEquals(1, $sequence->count());
        $sequence->remove(0);
        self::assertEquals(0, $sequence->count());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testFirst(string $type, $value1, $value2): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2]);
        self::assertEquals($value1, $sequence->first());
        $sequence->remove(0);
        self::assertEquals($value2, $sequence->first());
        $sequence->remove(0);
        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Unable to retrieve the first entry - sequence is empty');
        $sequence->first();
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testFreeze(string $type, $value1): void
    {
        $sequence = Sequence::of($type);
        $sequence->push($value1);
        $sequence->freeze();
        self::assertTrue($sequence->isFrozen());

        $thrownExceptions = [];

        try {
            $sequence->push(1);
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $sequence->pushMultiple([1,2,3]);
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $sequence->clear();
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $sequence->pop();
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $sequence->shift();
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $sequence->remove(0);
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $sequence->removeEntry($value1);
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $sequence->insert(1, $value1);
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }

        self::assertCount(8, $thrownExceptions);
        foreach ($thrownExceptions as $exception) {
            self::assertInstanceOf(FrozenException::class, $exception);
        }
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testGet(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3]);
        self::assertEquals($value1, $sequence->get(0));
        self::assertEquals($value2, $sequence->get(1));
        self::assertEquals($value3, $sequence->get(2));

        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Index 3 is out of bounds');
        $sequence->get(3);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testGetIterator(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3]);

        $i = 0;
        foreach ($sequence as $key => $value) {
            self::assertEquals($i, $key);
            self::assertEquals($value, ${'value' . ($i + 1)});
            $i++;
        }

        $iterator = $sequence->getIterator();
        self::assertInstanceOf(SequenceIterator::class, $iterator);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testGetType(string $type): void
    {
        $sequence = Sequence::of($type);
        self::assertEquals($type, $sequence->getType());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testHas(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3]);
        self::assertTrue($sequence->has(0));
        self::assertTrue($sequence->has(1));
        self::assertTrue($sequence->has(2));
        self::assertFalse($sequence->has(-1));
        self::assertFalse($sequence->has(4));
        $sequence->remove(1);
        self::assertTrue($sequence->has(1));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testHasEntry(string $type, $value1): void
    {
        $sequence = Sequence::of($type);
        self::assertFalse($sequence->hasEntry($value1));
        $sequence->push($value1);
        self::assertTrue($sequence->hasEntry($value1));
        $sequence->removeEntry($value1);
        self::assertFalse($sequence->hasEntry($value1));
        $sequence->push($value1);
        $sequence->push($value1);
        $sequence->removeEntry($value1);
        self::assertTrue($sequence->hasEntry($value1));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testIndexOf(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3, $value1]);

        self::assertEquals(0, $sequence->indexOf($value1));
        $sequence->remove(0);
        self::assertEquals(0, $sequence->indexOf($value2));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testInsert(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->insert(0, $value1);
        $sequence->insert(0, $value2);
        $sequence->insert(2, $value3);
        self::assertEquals($value2, $sequence->get(0));
        self::assertEquals($value1, $sequence->get(1));
        self::assertEquals($value3, $sequence->get(2));
    }

    public function testInsertOutOfBounds(): void
    {
        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Index 1 is out of bounds');

        $sequence = Sequence::of('string');
        $sequence->insert(1, 'value');
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testIsEmpty(string $type, $value1): void
    {
        $sequence = Sequence::of($type);
        self::assertTrue($sequence->isEmpty());
        $sequence->push($value1);
        self::assertFalse($sequence->isEmpty());
        $sequence->removeEntry($value1);
        self::assertTrue($sequence->isEmpty());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testIsOf(string $type): void
    {
        $sequence = Sequence::of($type);
        self::assertTrue($sequence->isOf($type));
        self::assertFalse($sequence->isOf('unknown'));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testLast(string $type, $value1, $value2): void
    {
        $sequence = Sequence::of($type);
        $sequence->push($value1);
        $sequence->push($value2);
        self::assertSame($value2, $sequence->last());
        $sequence->removeEntry($value2);
        self::assertSame($value1, $sequence->last());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testLastOutOfBounds(string $type): void
    {
        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Unable to retrieve the last entry - sequence is empty');

        $sequence = Sequence::of($type);
        $sequence->last();
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testPop(string $type, $value1, $value2): void
    {
        $sequence = Sequence::of($type);
        $sequence->push($value1);
        $sequence->push($value2);
        self::assertSame($value2, $sequence->pop());
        self::assertSame($value1, $sequence->pop());
        self::assertTrue($sequence->isEmpty());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testPopOutOfBounds(string $type): void
    {
        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Unable to pop an entry - sequence is empty');

        $sequence = Sequence::of($type);
        $sequence->pop();
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testPush(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->push($value1);
        $sequence->push($value2);
        $sequence->push($value3);

        self::assertCount(3, $sequence);
        self::assertEquals($value1, $sequence->get(0));
        self::assertEquals($value2, $sequence->get(1));
        self::assertEquals($value3, $sequence->get(2));
    }

    /**
     * @dataProvider valuesOfWrongTypeProvider
     */
    public function testPushWrongType(string $expectedType, string $actualType, $wrongValue): void
    {
        self::expectException(TypeException::class);
        self::expectExceptionMessage('Variable must be of type ' . $expectedType . ', ' . $actualType . ' given');

        $sequence = Sequence::of($expectedType);
        $sequence->push($wrongValue);
    }

    public function testPushWrongNullableType(): void
    {
        self::expectException(TypeException::class);
        self::expectExceptionMessage('Variable must be of type string or null, int given');

        $sequence = Sequence::of('?string');
        $sequence->push(1);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testPushMultiple(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3]);

        self::assertCount(3, $sequence);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testRemove(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3]);
        $sequence->remove(1);
        self::assertSame($value1, $sequence->get(0));
        self::assertSame($value3, $sequence->get(1));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testRemoveEntry(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3]);
        $sequence->push($value1);
        $sequence->removeEntry($value1);
        self::assertTrue($sequence->hasEntry($value1));
        $sequence->removeEntry($value1);
        $sequence->removeEntry($value2);
        $sequence->removeEntry($value3);
        self::assertFalse($sequence->hasEntry($value1));
        self::assertFalse($sequence->hasEntry($value2));
        self::assertFalse($sequence->hasEntry($value3));
        self::assertTrue($sequence->isEmpty());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testShift(string $type, $value1, $value2): void
    {
        $sequence = Sequence::of($type);
        $sequence->push($value1);
        $sequence->push($value2);
        self::assertSame($value1, $sequence->shift());
        self::assertSame($value2, $sequence->shift());
        self::assertTrue($sequence->isEmpty());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testShiftOutOfBounds(string $type): void
    {
        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Unable to shift an entry - sequence is empty');

        $sequence = Sequence::of($type);
        $sequence->shift();
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testToArray(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3]);
        $array = $sequence->toArray();
        self::assertCount(3, $array);
        self::assertSame($value1, $array[0]);
        self::assertSame($value2, $array[1]);
        self::assertSame($value3, $array[2]);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testUnshift(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->unshift($value1);
        $sequence->unshift($value2);
        $sequence->unshift($value3);
        self::assertSame($value3, $sequence->get(0));
        self::assertSame($value2, $sequence->get(1));
        self::assertSame($value1, $sequence->get(2));
    }
}