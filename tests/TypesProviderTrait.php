<?php

declare(strict_types=1);

namespace Tests\Krlove;

use ArrayIterator;
use Tests\Krlove\Stub\Obj1;
use Tests\Krlove\Stub\Obj2;
use Tests\Krlove\Stub\Obj3;

trait TypesProviderTrait
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

    public function typesDataProvider(): array
    {
        $this->resources[] = $r1 = fopen(__DIR__ . '/resources/test.txt', 'r');
        $this->resources[] = $r2 = fopen(__DIR__ . '/resources/test.txt', 'r+');
        $this->resources[] = $r3 = fopen(__DIR__ . '/resources/test.txt', 'w');

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
        $r = fopen(__DIR__ . '/resources/test.txt', 'r');
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

    public function keyValueTypesDataProvider(): array
    {
        $typesData = $this->typesDataProvider();

        $data = [];
        foreach ($typesData as $key => $keyData) {
            foreach ($typesData as $value => $valueData) {
                $data[$key . '-' . $value] = [
                    'keyType' => $keyData['type'],
                    'valueType' => $valueData['type'],
                    'key1' => $keyData['value1'],
                    'value1' => $valueData['value1'],
                    'key2' => $keyData['value2'],
                    'value2' => $valueData['value2'],
                    'key3' => $keyData['value3'],
                    'value3' => $valueData['value3'],
                ];
            }
        }

        return $data;
    }
}
