# Collection

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
//$sequence->push('Bilbo'); Fatal error: Uncaught Krlove\Collection\Exception\FrozenException: Krlove\Collection\Sequence\Sequence is frozen and can not be changed
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

`clear(): void`

Clears the sequence

`copy(): self`

Creates new instance of this `Sequence` and copies all entries to the copy. Copy is always unfrozen.

`count(): int`

Returns number of entries in the sequence

`first(): T`

Returns the first entry in the sequence. Throws `OutOfBoundsException` if the sequence is empty

`get(int $index): T`

Returns the entry by index. Throws `OutOfBoundsException` if there is no such index

`getIterator(): \Trawersable`

Returns iterator for given sequence

`getType(): string`

Returns type of entries of the sequence. Notice that if the type is nullable, it still will be returned without a question mark prefix
```php
$sequence = Sequence::of('?string');
$type = $sequence->getType(); // 'string'
```
To check whether type is nullable, use `Sequence::isNullable`
