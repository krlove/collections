<?php

declare(strict_types=1);

namespace Tests\Krlove\Sequence;

use Exception;
use Krlove\Collections\Exception\FrozenException;
use Krlove\Collections\Exception\OutOfBoundsException;
use Krlove\Collections\Exception\TypeException;
use Krlove\Collections\Iterator\SequenceIterator;
use Krlove\Collections\Sequence\Sequence;
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
        self::assertEquals($type, (string) $sequence->getType());
    }

    /**
     * @dataProvider nullableTypesDataProvider
     */
    public function testOfNullable(string $actualType, string $expectedType): void
    {
        $sequence = Sequence::of($actualType);
        self::assertEquals($expectedType, (string) $sequence->getType());
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
        self::assertCount(1, $sequence);
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
     * @dataProvider nullableTypesDataProvider
     */
    public function testCopyNullable(string $actualType): void
    {
        $sequence = Sequence::of($actualType);

        $copy = $sequence->copy();
        self::assertTrue($copy->getType()->isNullable());
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

    public function testFilter(): void
    {
        $sequence = Sequence::of('int');
        $sequence->pushMultiple([10, 20, 30, 40, 50, 60, 70]);

        $filtered = $sequence->filter(function (int $entry) {
            return $entry > 45;
        });

        self::assertCount(3, $filtered);
        self::assertEquals(50, $filtered->get(0));
        self::assertEquals(60, $filtered->get(1));
        self::assertEquals(70, $filtered->get(2));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testFilterWithIndex(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3]);
        
        $filtered = $sequence->filter(function ($entry, int $index) {
            return $index % 2 == 0;
        });
        
        self::assertCount(2, $filtered);
        self::assertEquals($value1, $filtered->get(0));
        self::assertEquals($value3, $filtered->get(1));
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
            self::assertEquals('Sequence is frozen and can not be changed', $exception->getMessage());
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

        self::assertInstanceOf(SequenceIterator::class, $sequence->getIterator());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testGetType(string $type): void
    {
        $sequence = Sequence::of($type);
        self::assertEquals($type, (string) $sequence->getType());
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
    public function testIndexOfWrongEntry(string $type, $value1): void
    {
        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Entry not found in the Sequence');

        $sequence = Sequence::of($type);
        $sequence->indexOf($value1);
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

    public function testMap(): void
    {
        $sequence = Sequence::of('int');
        $sequence->pushMultiple([1, 2, 3, 4, 5]);

        $mapped = $sequence->map(function ($entry) {
            return $entry * 2;
        });

        self::assertIsArray($mapped);
        self::assertCount(5, $mapped);
        self::assertSame([2,4,6,8,10], $mapped);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testMapWithIndex(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3]);

        $mapped = $sequence->map(function ($entry, $index) {
            return $index;
        });

        self::assertSame([0, 1, 2], $mapped);
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
     * @dataProvider nullableTypesDataProvider
     */
    public function testPushNullable(string $actualType): void
    {
        $sequence = Sequence::of($actualType);
        $sequence->push(null);

        self::assertCount(1, $sequence);
        self::assertNull($sequence->pop());
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
        self::expectExceptionMessage('Variable must be of type ?string, int given');

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

    public function testReduce(): void
    {
        $sequence = Sequence::of('int');
        $sequence->pushMultiple([1, 2, 3, 4, 5]);

        $reduced = $sequence->reduce(function ($carry, $entry) {
            return $carry + $entry;
        }, 5);

        self::assertEquals(20, $reduced);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testReduceWithIndex(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3]);

        $reduced = $sequence->reduce(function ($carry, $entry, int $index) {
            return $carry + $index;
        }, 0);

        self::assertEquals(3, $reduced);
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
    public function testRemoveEntryNotInSequence(string $type, $value1): void
    {
        $sequence = Sequence::of($type);
        self::assertFalse($sequence->removeEntry($value1));
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
     * @dataProvider sliceDataProvider
     */
    public function testSlice(array $entries, int $offset, ?int $length, array $expected): void
    {
        $sequence = Sequence::of('string');
        $sequence->pushMultiple($entries);

        $sliced = $sequence->slice($offset, $length);
        self::assertSame($expected, $sliced->toArray());
    }

    public function sliceDataProvider(): array
    {
        return [
            [
                'entries' => ['a', 'b', 'c', 'd', 'e', 'f'],
                'offset' => 2,
                'length' => null,
                'expected' => ['c', 'd', 'e', 'f'],
            ],
            [
                'entries' => ['a', 'b', 'c', 'd', 'e', 'f'],
                'offset' => -1,
                'length' => 3,
                'expected' => ['f'],
            ],
            [
                'entries' => ['a', 'b', 'c', 'd', 'e', 'f'],
                'offset' => 1,
                'length' => -3,
                'expected' => ['b', 'c'],
            ],
            [
                'entries' => [],
                'offset' => 1,
                'length' => 2,
                'expected' => [],
            ],
        ];
    }

    public function testSort(): void
    {
        $sequence = Sequence::of('int');
        $sequence->pushMultiple([5, 3, 7, 2, 3, 4, 9, 1, 1, 0, 8]);

        $sequence->sort(function ($entry1, $entry2) {
            return $entry1 - $entry2;
        });
        self::assertSame([0, 1, 1, 2, 3, 3, 4, 5, 7, 8, 9], $sequence->toArray());
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
    public function testUnique(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3, $value1, $value2, $value3]);

        $unique = $sequence->unique();
        self::assertContains($value1, $unique);
        self::assertContains($value2, $unique);
        self::assertContains($value3, $unique);

        $count = $type === 'null' ? 1 : ($type === 'bool' ? 2 : 3);
        self::assertCount($count, $unique);
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

    /**
     * @dataProvider typesDataProvider
     */
    public function testWalk(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->pushMultiple([$value1, $value2, $value3]);

        $array = [];
        $sequence->walk(function ($entry, int $index) use (&$array) {
            $array[$index] = $entry;
        });

        self::assertCount(3, $array);
        self::assertSame([$value1, $value2, $value3], $array);
    }
}