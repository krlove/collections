<?php

declare(strict_types=1);

namespace Tests\Krlove\Set;

use ArrayIterator;
use Exception;
use Krlove\Collections\Exception\FrozenException;
use Krlove\Collections\Exception\OutOfBoundsException;
use Krlove\Collections\Exception\TypeException;
use Krlove\Collections\Set\Set;
use PHPUnit\Framework\TestCase;
use Tests\Krlove\TypesProviderTrait;

class SetTest extends TestCase
{
    use TypesProviderTrait;

    /**
     * @dataProvider typesDataProvider
     */
    public function testOf(string $type): void
    {
        $set = Set::of($type);
        self::assertEquals($type, (string) $set->getType());
    }

    /**
     * @dataProvider nullableTypesDataProvider
     */
    public function testOfNullable(string $actualType, string $expectedType): void
    {
        $set = Set::of($actualType);
        self::assertEquals($expectedType, $set->getType());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testAdd(string $type, $value1, $value2, $value3): void
    {
        $set = Set::of($type);
        $set->add($value1);
        $set->add($value2);
        $set->add($value3);
        self::assertFalse($set->isEmpty());
        self::assertTrue($set->contains($value1));
        self::assertTrue($set->contains($value2));
        self::assertTrue($set->contains($value3));
    }

    /**
     * @dataProvider nullableTypesDataProvider
     */
    public function testAddNullable(string $actualType): void
    {
        $set = Set::of($actualType);
        $set->add(null);
        $set->add(null);

        self::assertCount(1, $set);
        self::assertContains(null, $set);
        self::assertNull($set->pop());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testAddMultiple(string $type, $value1, $value2, $value3): void
    {
        $set = Set::of($type);
        $set->addMultiple([$value1, $value2, $value3]);
        self::assertFalse($set->isEmpty());
        self::assertTrue($set->contains($value1));
        self::assertTrue($set->contains($value2));
        self::assertTrue($set->contains($value3));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testClear(string $type, $value1): void
    {
        $set = Set::of($type);
        $set->add($value1);
        self::assertCount(1, $set);
        $set->clear();
        self::assertCount(0, $set);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testContains(string $type, $value1, $value2, $value3): void
    {
        $set = Set::of($type);
        $set->addMultiple([$value1, $value2, $value3]);
        self::assertTrue($set->contains($value1));
        self::assertTrue($set->contains($value2));
        self::assertTrue($set->contains($value3));
        $set->remove($value1);
        $set->remove($value2);
        $set->remove($value3);
        self::assertFalse($set->contains($value1));
        self::assertFalse($set->contains($value2));
        self::assertFalse($set->contains($value3));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testCopy(string $type, $value1): void
    {
        $set = Set::of($type);
        $set->add($value1);

        $copy = $set->copy();
        self::assertNotSame($set, $copy);
        self::assertCount(1, $copy);
        self::assertSame($value1, $copy->pop());
    }

    /**
     * @dataProvider nullableTypesDataProvider
     */
    public function testCopyNullable(string $actualType, string $expectedType): void
    {
        $set = Set::of($actualType);
        $copy = $set->copy();
        $copy->add(null);
        self::assertTrue($copy->isOf($expectedType));
        self::assertContains(null, $copy);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testCount(string $type, $value1): void
    {
        $set = Set::of($type);
        self::assertEquals(0, $set->count());
        $set->add($value1);
        self::assertEquals(1, $set->count());
        $set->add($value1);
        self::assertEquals(1, $set->count());
        $set->remove($value1);
        self::assertEquals(0, $set->count());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testDifference(string $type, $value1, $value2, $value3): void
    {
        $set1 = Set::of($type);
        $set1->addMultiple([$value1, $value2]);

        $set2 = Set::of($type);
        $set2->addMultiple([$value2, $value3]);

        $diffSet = $set1->difference($set2);
        self::assertInstanceOf(Set::class, $diffSet);

        if ($type === 'null' || $type === 'bool') {
            self::assertCount(0, $diffSet);

            return;
        }

        self::assertCount(1, $diffSet);
        self::assertTrue($diffSet->contains($value1));
    }

    public function testDifferenceWrongTypes(): void
    {
        self::expectException(TypeException::class);
        self::expectExceptionMessage('Unable to perform operation: types string and int are not compatible');

        $set1 = Set::of('string');
        $set2 = Set::of('int');
        $set1->difference($set2);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testFreeze(string $type, $value1): void
    {
        $set = Set::of($type);
        $set->add($value1);
        $set->freeze();
        self::assertTrue($set->isFrozen());

        $thrownExceptions = [];

        try {
            $set->add(1);
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $set->addMultiple([1,2,3]);
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $set->clear();
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $set->pop();
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }
        try {
            $set->remove(1);
        } catch (Exception $e) {
            $thrownExceptions[] = $e;
        }

        self::assertCount(5, $thrownExceptions);
        foreach ($thrownExceptions as $exception) {
            self::assertInstanceOf(FrozenException::class, $exception);
            self::assertEquals('Set is frozen and can not be changed', $exception->getMessage());
        }
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testGetIterator(string $type, $value1, $value2, $value3): void
    {
        $set = Set::of($type);
        $set->addMultiple([$value1, $value2, $value3]);

        $i = 0;
        foreach ($set as $value) {
            self::assertEquals($value, ${'value' . ($i + 1)});
            $i++;
        }

        self::assertInstanceOf(ArrayIterator::class, $set->getIterator());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testGetType(string $type): void
    {
        $set = Set::of($type);
        self::assertEquals($type, (string) $set->getType());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testHasIntersectionWith(string $type, $value1, $value2, $value3): void
    {
        $set1 = Set::of($type);
        $set1->addMultiple([$value1, $value2]);

        $set2 = Set::of($type);
        $set2->addMultiple([$value2, $value3]);

        self::assertTrue($set1->hasIntersectionWith($set2));
        self::assertTrue($set2->hasIntersectionWith($set1));
    }

    public function testHasIntersectionDifferentTypes(): void
    {
        $set1 = Set::of('int');
        $set1->add(1);
        $set2 = Set::of('float');
        $set2->add(1.0);

        self::assertFalse($set1->hasIntersectionWith($set2));
        self::assertFalse($set2->hasIntersectionWith($set1));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testIntersection(string $type, $value1, $value2, $value3): void
    {
        $set1 = Set::of($type);
        $set1->addMultiple([$value1, $value2]);

        $set2 = Set::of($type);
        $set2->addMultiple([$value2, $value3]);

        $intersectionSet = $set1->intersection($set2);

        if ($type === 'bool') {
            self::assertCount(2, $intersectionSet);
            self::assertTrue($intersectionSet->contains($value1));
            self::assertTrue($intersectionSet->contains($value2));

            return;
        }

        self::assertCount(1, $intersectionSet);
        self::assertTrue($intersectionSet->contains($value2));
    }

    public function testNoIntersection(): void
    {
        $set1 = Set::of('string');
        $set1->add('Gandalf');

        $set2 = Set::of('string');
        $set2->add('Frodo');

        $intersectionSet = $set1->intersection($set2);
        self::assertTrue($intersectionSet->isEmpty());
    }

    public function testIntersectionWrongTypes(): void
    {
        self::expectException(TypeException::class);
        self::expectExceptionMessage('Unable to perform operation: types string and int are not compatible');

        $set1 = Set::of('string');
        $set2 = Set::of('int');
        $set1->intersection($set2);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testIsEmpty(string $type, $value1): void
    {
        $set = Set::of($type);
        self::assertTrue($set->isEmpty());
        $set->add($value1);
        self::assertFalse($set->isEmpty());
        $set->remove($value1);
        self::assertTrue($set->isEmpty());
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testIsOf(string $type): void
    {
        $set = Set::of($type);
        self::assertTrue($set->isOf($type));
        self::assertFalse($set->isOf('unknown'));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testIsSubsetOf(string $type, $value1, $value2, $value3): void
    {
        $set1 = Set::of($type);
        $set1->addMultiple([$value1, $value2, $value3]);

        $set2 = Set::of($type);
        $set2->addMultiple([$value1, $value3]);

        self::assertTrue($set2->isSubsetOf($set1));
        if ($type !== 'null') {
            self::assertFalse($set1->isSubsetOf($set2));
        }
        self::assertTrue($set1->isSubsetOf($set1));
    }

    public function testIsSubsetOfDifferentTypes(): void
    {
        $set1 = Set::of('int');
        $set1->add(1);

        $set2 = Set::of('float');
        $set2->add(1.0);

        self::assertFalse($set1->isSubsetOf($set2));
        self::assertFalse($set2->isSubsetOf($set1));
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testPop(string $type, $value1, $value2): void
    {
        $set = Set::of($type);
        $set->addMultiple([$value1, $value2]);

        $val = $set->pop();
        self::assertContains($val, [$value1, $value2]);
        if ($type !== 'null') {
            $val = $set->pop();
            self::assertContains($val, [$value1, $value2]);
        }

        self::assertTrue($set->isEmpty());
    }

    public function testPopEmptySet(): void
    {
        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Can not pop from an empty Set');

        $set = Set::of('string');
        $set->pop();
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testRemove(string $type, $value1): void
    {
        $set = Set::of($type);
        $set->add($value1);
        self::assertTrue($set->remove($value1));
        self::assertFalse($set->remove($value1));
        self::assertEmpty($set);
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testToArray(string $type, $value1, $value2, $value3): void
    {
        $set = Set::of($type);
        $set->addMultiple([$value1, $value2, $value3]);

        $array = $set->toArray();
        foreach ($array as $value) {
            self::assertContains($value, [$value1, $value2, $value3]);
        }
    }

    /**
     * @dataProvider typesDataProvider
     */
    public function testUnion(string $type, $value1, $value2, $value3): void
    {
        $set1 = Set::of($type);
        $set1->addMultiple([$value1, $value2]);

        $set2 = Set::of($type);
        $set2->addMultiple([$value2, $value3]);

        $unionSet = $set1->union($set2);
        self::assertTrue($unionSet->contains($value1));
        self::assertTrue($unionSet->contains($value2));
        self::assertTrue($unionSet->contains($value3));
    }

    public function testUnionWrongTypes(): void
    {
        self::expectException(TypeException::class);
        self::expectExceptionMessage('Unable to perform operation: types string and int are not compatible');

        $set1 = Set::of('string');
        $set2 = Set::of('int');
        $set1->union($set2);
    }
}
