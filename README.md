# Collections

**Strictly typed** data structures for PHP.

## Installation

```
composer require krlove/collections
```

## Sequence
Sequence is an ordered collection of variables of any type.
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

## Map
Map contains key-value pairs, where each key is unique.
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

## Set
Set is a collection of unique variables of any type.
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

## Types
All collections are strictly typed.
```php
$sequence = Sequence::of('int');
$sequence->push('Gandalf');
```
```
PHP Fatal error:  Uncaught Krlove\Collection\Exception\TypeException: Variable must be of type int, string given
```

Supported types are:
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

## Nullable
Types can be nullable.
```php
$sequence = Sequence::of('?string');
$sequence->push(null);
```

## Freezable
After collection is "frozen", it becomes read-only, no changes are allowed to it. It is impossible to "unfreeze" the collection once it is frozen, but it is possible to **copy** it.
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

## Usage
PHP does not support generic types, so it is impossible to define a property as follows.
```php
private Map<int, string> $map; // invalid
```
Some additional code must be written to ensure collections of proper types are used
```php
class MyClass
{
    /**
     * @var Map<int, string> 
     */
    private Map $map;
    
    public function __construct()
    {
        $this->map = Map::of('int', 'string');
    }
    
    /**
     * @param Map<int, string> $map
     * @return void
     */
    public function setMap(Map $map): void
    {
        if (!$map->isOf('int', 'string')) {
            // throw exception
        }
        
        $this->map = $map;
    }
}
```
