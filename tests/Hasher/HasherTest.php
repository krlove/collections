<?php

declare(strict_types=1);

namespace Tests\Krlove\Hasher;

use Krlove\Collections\Hasher\Hasher;
use PHPUnit\Framework\TestCase;
use Tests\Krlove\Stub\Obj1;
use Tests\Krlove\Stub\Obj2;

class HasherTest extends TestCase
{
    public function testHashUnique(): void
    {
        $r1 = fopen(__DIR__ . '/../resources/test.txt', 'r');
        $r2 = fopen(__DIR__ . '/../resources/test.txt', 'r+');
        $r3 = fopen(__DIR__ . '/../resources/test.txt', 'w+');
        fclose($r3);

        $values = [
            null,
            true,
            false,
            0,
            100,
            -100,
            PHP_INT_MIN,
            PHP_INT_MAX,
            0.0,
            100.0,
            -100.0,
            PHP_FLOAT_MIN,
            PHP_FLOAT_MAX,
            '',
            '0',
            '100',
            '-100',
            [],
            ['foo'],
            ['bar'],
            ['foo' => 'bar'],
            function () {},
            function () {},
            function () { return true; },
            $r1,
            $r2,
            $r3,
            new Obj1(1),
            new Obj1(1),
            new Obj1(2),
            new Obj2(3),
        ];

        $hashedValues = [];
        foreach ($values as $value) {
            $hashedValue = Hasher::hash($value);
            $hashedValues[$hashedValue] = true;
        }

        self::assertSameSize($values, $hashedValues);
        fclose($r1);
        fclose($r2);
    }
}
