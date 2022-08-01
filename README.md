# Collection (Work in progress)

**Strictly typed** data structures for PHP

### Installation

```
composer require krlove/collection
```

### Sequence
Sequence is an ordered collection of variables of any type
```php
$sequence = Sequence::of('string');
$sequence->push('Gandalf');
$sequence->push('Bilbo');
$sequence->push('Frodo');
$sequence->remove(1);

foreach ($sequence as $index => $value) {
    echo $index . ': ' . $value . PHP_EOL;
}
```
```
0: Gandalf
1: Frodo
```

### Map
Map contains key-value pairs, where each key is unique
```php
$map = Map::of('string', 'array');
$map->set('fruits', ['apple', 'banana', 'pear']);
$map->set('vegetables', ['tomato', 'potato', 'onion']);
$map->set('berries', ['strawberry', 'blueberry', 'raspberry']);
$map->remove('vegetables');
$map->set('berries', ['bilberry']);

foreach ($map as $key => $value) {
    echo $key . ': ' . var_export($value, true) . PHP_EOL;
}
```
```
fruits: array (
  0 => 'apple',
  1 => 'banana',
  2 => 'pear',
)
berries: array (
  0 => 'bilberry',
)
```

### Set
Set is a collection of unique variables of any type
```php
$set = Set::of('string');
$set->add('Gandalf');
$set->add('Bilbo');
$set->add('Bilbo');

echo var_export($set->toArray(), true) . PHP_EOL;
```
```
array (
  0 => 'Gandalf',
  1 => 'Bilbo',
)
```

### Types
All collections are strictly typed
```php
$sequence = Sequence::of('int');
$sequence->push('Gandalf');
```
```
PHP Fatal error:  Uncaught Krlove\Collection\Exception\TypeException: Variable must be of type int, string given
```

Supported types are
- null
- bool
- int
- float
- string
- array
- iterable
- callable
- resource
- object
- class (objects of specific class or interface)
- mixed (any type is allowed)

### Nullable
Types can be nullable
```php
$sequence = Sequence::of('?string');
$sequence->push(null);
```

### Freezable
After collection is "frozen", it becomes read-only, no changes are allowed to it. It is impossible to "unfreeze" the collection once it is frozen, but it is possible to **copy** it
```php
$sequence = Sequence::of('string');
$sequence->push('Gandalf');
$sequence->freeze();
//$sequence->push('Bilbo'); Fatal error: Uncaught Krlove\Collection\Exception\FrozenException: Sequence is frozen and can not be changed
$copy = $sequence->copy();
$copy->push('Bilbo');

foreach ($copy as $index => $value) {
    echo $index . ': ' . $value . PHP_EOL;
} 
```
```
0: Gandalf
1: Bilbo
```

### Usage
Unfortunately, PHP does not support generic types, so it is impossible to define a property as follows
```php
private Map<int, string> $map; // invalid
```
Some additional code must be written to ensure collections of proper types are used
```php
class MyClass
{
    private Map $map;
    
    public function __construct()
    {
        $this->map = Map::of('int', 'string');
    }
    
    public function setMap(Map $map): void
    {
        if (!$map->isOf('int', 'string')) {
            // throw exception
        }
        
        $this->map = $map;
    }
}
```

### API Reference

### Sequence

**clear(): void**

Clears the sequence. Throws `FrozenException` is the sequence is frozen.

**copy(): self**

Creates new instance of this `Sequence` and copies all entries to the copy. Copy is always unfrozen.

**count(): int**

Returns number of entries in the sequence

**first(): T**

Returns the first entry in the sequence. Throws `OutOfBoundsException` if the sequence is empty

**get(int $index): T**

Returns the entry by index. Throws `OutOfBoundsException` if there is no such index

**getIterator(): Traversable**

Returns iterator for given sequence

**getType(): TypeInterface**

Returns type of entries of the sequence
```php
$sequence = Sequence::of('?string');
$type = $sequence->getType();
(string) $type; // '?string'
$type->getType(); // 'string'
$type->isNullable(); // true
```

**has(int $index): bool**

Returns `true` is the sequence contains an entry with given index, `false` otherwise

**hasEntry($entry): bool**

Checks if the sequence contains given entry

**indexOf($entry): int**

Returns an index of entry, if it exists in the sequence. If the sequence contains multiple entries `$entry`, then the index of the first one will be returned. Throws `OutOfBoundsException` if the entry is not present in the sequence

**insert(int $index, $entry): void**

Inserts the entry to a given position (index). All entries after this index will be shifted to the right. Throws `FrozenException` if the sequence is frozen. Throws `TypeException` if the entry is of a wrong type. Throws `OutOfBoundsException` if given index is out of range of existing indices.

**isEmpty(): bool**

Return `true` is the sequence is empty

**isOf(string $type): bool**

Checks if the sequence is of given type

**last(): T**

Returns the last entry in the sequence. Throws `OutOfBoundsException` is the sequence is empty

**pop(): T**

Removes and returns the last entry of the sequence. Throws `FrozenException` if the sequence is frozen. Throws `OutOfBoundsException` if the sequence is empty

**push($entry): void**

Adds the entry to the end of the sequence. Throws `FrozenException` if the sequence is frozen. Throws `TypeException` if the entry is of a wrong type

**pushMultiple(array $entries): void**

Adds multiple entries to the end of the sequence. Throws `FrozenException` if the sequence is frozen. Throws `TypeException` if any of the entries is of a wrong type

**remove(int $index): bool**

Removes an entry at given index. Throws `FrozenException` if the sequence is frozen. Returns `true` if an entry with given index existed and was successfully removed, `false` otherwise

**removeEntry($entry): bool**

Removes the entry from the sequence. If the sequence contains multiple entries `$entry`, the first one will be removed. Throws `FrozenException` if the sequence is frozen. Returns `true` if the entry was found and removed, `false` otherwise

**shift(): T**

Removes and returns the first entry of the sequence. Throws `FrozenException` if the sequence is frozen. Throws `OutOfBoundsException` if the sequence is empty

**toArray(): array**

Returns an array, containing all entries of the sequence

**unshift($entry): void**

Adds the entry to the beginning of the sequence. Moves all existing entries to the right. Throws `FrozenException` if the sequence is frozen. Throws `TypeException` if the entry is of a wrong type
