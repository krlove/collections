<?php

declare(strict_types=1);

namespace Krlove\Collections\Map;

/**
 * @psalm-template TKey
 * @psalm-template TValue
 */
class Pair
{
    /**
     * @psalm-var TKey
     */
    private $key;

    /**
     * @psalm-var TValue
     */
    private $value;

    /**
     * @param TKey $key
     * @psalm-param TKey $key
     * @param TValue $value
     * @psalm-param TValue $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return TKey
     * @psalm-return TKey
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return TValue
     * @psalm-return TValue
     */
    public function getValue()
    {
        return $this->value;
    }
}
