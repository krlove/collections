<?php

declare(strict_types=1);

namespace Type;

use Krlove\Collection\Type\TypeFactory;
use Krlove\Collection\Type\TypeIntersection;
use PHPUnit\Framework\TestCase;

class TypeIntersectionTest extends TestCase
{
    /**
     * @dataProvider typesProvider
     */
    public function testBetween(string $type1, string $type2, string $intersectionType): void
    {
        self::assertEquals(
            $intersectionType,
            TypeIntersection::between(TypeFactory::create($type1), TypeFactory::create($type2))
        );
    }

    public function typesProvider(): array
    {
        return [
            [
                'type1' => 'int',
                'type2' => 'int',
                'intersectionType' => 'int',
            ],
            [
                'type1' => 'int',
                'type2' => '?int',
                'intersectionType' => '?int',
            ],
            [
                'type1' => 'mixed',
                'type2' => 'int',
                'intersectionType' => 'mixed',
            ],
            [
                'type1' => 'mixed',
                'type2' => '?mixed',
                'intersectionType' => 'mixed',
            ],
        ];
    }
}
