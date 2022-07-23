<?php

declare(strict_types=1);

namespace Tests\Krlove\Sequence;

use ArrayIterator;
use Exception;
use Krlove\Collection\Exception\FrozenException;
use Krlove\Collection\Exception\OutOfBoundsException;
use Krlove\Collection\Exception\TypeException;
use Krlove\Collection\Iterator\DoublyLinkedListIterator;
use Krlove\Collection\Sequence\Sequence;
use Krlove\Collection\Set\Set;
use PHPUnit\Framework\TestCase;
use Tests\Krlove\Stub\Obj1;
use Tests\Krlove\Stub\Obj2;
use Tests\Krlove\Stub\Obj3;

class SequenceTest extends TestCase
{
    private array $resources = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->resources = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        foreach ($this->resources as $resource) {
            fclose($resource);
        }
    }

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
        self::assertInstanceOf(DoublyLinkedListIterator::class, $iterator);
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

    public function typesDataProvider(): array
    {
        $this->resources[] = $r1 = fopen(__DIR__ . '/../resources/test.txt', 'r');
        $this->resources[] = $r2 = fopen(__DIR__ . '/../resources/test.txt', 'r+');
        $this->resources[] = $r3 = fopen(__DIR__ . '/../resources/test.txt', 'w');

        return [
            'null' => [
                'type' => 'null',
                'value1' => null,
                'value2' => null,
                'value3' => null,
            ],
            'bool' => [
                'type' => 'bool',
                'value1' => true,
                'value2' => false,
                'value3' => true,
            ],
            'int' => [
                'type' => 'int',
                'value1' => 100,
                'value2' => 200,
                'value3' => 300,
            ],
            'float' => [
                'type' => 'float',
                'value1' => 0.45,
                'value2' => -1e3,
                'value3' => floatval(1_200),
            ],
            'string' => [
                'type' => 'string',
                'value1' => 'Harry',
                'value2' => 'Hermione',
                'value3' => 'Ron',
            ],
            'object' => [
                'type' => 'object',
                'value1' => new Obj1(1),
                'value2' => new Obj2(2),
                'value3' => new Obj3(3),
            ],
            'callable' => [
                'type' => 'callable',
                'value1' => function () {},
                'value2' => [$this, 'typeDataProvider'],
                'value3' => 'in_array',
            ],
            'iterable' => [
                'type' => 'iterable',
                'value1' => ['foo' => 'bar'],
                'value2' => new ArrayIterator(['a' => 'b']),
                'value3' => [],
            ],
            'resource' => [
                'type' => 'resource',
                'value1' => $r1,
                'value2' => $r2,
                'value3' => $r3,
            ],
            Obj1::class => [
                'type' => Obj1::class,
                'value1' => new Obj1(1),
                'value2' => new Obj1(2),
                'value3' => new Obj1(3),
            ],
            'mixed' => [
                'type' => 'mixed',
                'value1' => true,
                'value2' => 'Harry',
                'value3' => new Obj1(1),
            ],
        ];
    }

    public function valuesOfWrongTypeProvider(): array
    {
        $r = fopen(__DIR__ . '/../resources/test.txt', 'r');
        $this->resources[] = $r;

        $typeValues = [
            'null' => null,
            'bool' => true,
            'int' => 100,
            'float' => 1e5,
            'string' => 'Harry',
            'object' => new Obj1(1),
            'iterable' => ['foo' => 'bar'],
            'resource' => $r,
            'callable' => function () {},
            Obj2::class => new Obj2(2),
        ];

        $data = [];
        foreach ($typeValues as $key => $value) {
            foreach ($typeValues as $type => $wrongValue) {
                if ($type === $key) {
                    continue;
                }

                if ($key === 'object' && $type === 'callable') {
                    continue;
                }

                if ($key === 'object' && $type === Obj2::class) {
                    continue;
                }

                if ($key === 'callable' && $type === 'string') {
                    continue;
                }

                if ($type === 'object') {
                    $type = get_class($wrongValue);
                }

                $data[] = [
                    'expectedType' => $key,
                    'actualType' => $type,
                    'wrongValue' => $wrongValue,
                ];
            }
        }

        return $data;
    }
}