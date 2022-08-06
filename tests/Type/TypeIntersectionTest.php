<?php

declare(strict_types=1);

namespace Tests\Krlove\Type;

use Krlove\Collections\Type\TypeFactory;
use Krlove\Collections\Type\TypeIntersection;
use PHPUnit\Framework\TestCase;
use Tests\Krlove\Type\Stub\ChildClass;
use Tests\Krlove\Type\Stub\ChildInterface;
use Tests\Krlove\Type\Stub\ClassImplInterface;
use Tests\Krlove\Type\Stub\ParentClass;
use Tests\Krlove\Type\Stub\ParentInterface;

class TypeIntersectionTest extends TestCase
{
    /**
     * @dataProvider typesProvider
     */
    public function testBetween(string $type1, string $type2, ?string $intersectionType): void
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
            [
                'type1' => ChildClass::class,
                'type2' => ParentClass::class,
                'intersectionType' => ParentClass::class,
            ],
            [
                'type1' => ParentClass::class,
                'type2' => '?' . ChildClass::class,
                'intersectionType' => '?' . ParentClass::class,
            ],
            [
                'type1' => ChildInterface::class,
                'type2' => ParentInterface::class,
                'intersectionType' => ParentInterface::class,
            ],
            [
                'type1' => ClassImplInterface::class,
                'type2' => ChildInterface::class,
                'intersectionType' => ChildInterface::class,
            ],
            [
                'type1' => ClassImplInterface::class,
                'type2' => ParentInterface::class,
                'intersectionType' => ParentInterface::class,
            ],
            [
                'type1' => ChildInterface::class,
                'type2' => ChildClass::class,
                'intersectionType' => null,
            ],
            [
                'type1' => 'array',
                'type2' => 'iterable',
                'intersectionType' => 'iterable',
            ],
            [
                'type1' => 'int',
                'type2' => 'null',
                'intersectionType' => '?int',
            ],
            [
                'type1' => 'null',
                'type2' => 'null',
                'intersectionType' => 'null',
            ],
            [
                'type1' => '?int',
                'type2' => '?null',
                'intersectionType' => '?int',
            ],
            [
                'type1' => 'mixed',
                'type2' => 'null',
                'intersectionType' => 'mixed',
            ],
            [
                'type1' => 'string',
                'type2' => 'int',
                'intersectionType' => null,
            ],
            [
                'type1' => 'object',
                'type2' => 'callable',
                'intersectionType' => null,
            ],
            [
                'type1' => 'array',
                'type2' => 'bool',
                'intersectionType' => null,
            ],
        ];
    }
}
