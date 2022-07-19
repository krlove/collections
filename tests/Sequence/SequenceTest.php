<?php

declare(strict_types=1);

namespace Tests\Krlove\Sequence;

use ArrayIterator;
use Krlove\Collection\Exception\TypeException;
use Krlove\Collection\Sequence\Sequence;
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
    public function testAdd(string $type, $value1, $value2, $value3): void
    {
        $sequence = Sequence::of($type);
        $sequence->add($value1);
        $sequence->add($value2);
        $sequence->add($value3);

        self::assertCount(3, $sequence);
        self::assertEquals($value1, $sequence->get(0));
        self::assertEquals($value2, $sequence->get(1));
        self::assertEquals($value3, $sequence->get(2));
    }

    public function testAddWrongType(): void
    {
        self::expectException(TypeException::class);
        self::expectExceptionMessage('Variable must be of type float');

        $sequence = Sequence::of('float');
        $sequence->add(1);

        self::expectException(TypeException::class);
        self::expectExceptionMessage('Variable must be of type \Tests\Krlove\Stub\Key');

        $sequence = Sequence::of(Obj1::class);
        $sequence->add(1);
    }

    public function typesDataProvider(): array
    {
        $r1 = fopen(__DIR__ . '/../resources/test.txt', 'r');
        $r2 = fopen(__DIR__ . '/../resources/test.txt', 'r+');
        $r3 = fopen(__DIR__ . '/../resources/test.txt', 'w');
        $this->resources = [$r1, $r2, $r3];

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
                'value3' => 100,
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
        ];
    }

    public function valuesOfWrongTypeProvider(): array
    {
        return [
            // continue here
        ];
    }
}