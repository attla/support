<?php

namespace Attla\Support;

use Illuminate\Support\Enumerable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\{
    Arrayable,
    Jsonable
};

class Arr
{
    /**
     * Convert a value to array
     *
     * @param mixed $value
     * @return array
     */
    public static function toArray($value): array
    {
        if (is_array($value)) {
            return $value;
        } elseif ($value instanceof Model) {
            return $value->getAttributes();
        } elseif ($value instanceof Enumerable) {
            return $value->all();
        } elseif ($value instanceof Arrayable) {
            return $value->toArray();
        } elseif ($value instanceof Jsonable) {
            return json_decode($value->toJson(), true);
        } elseif ($value instanceof \JsonSerializable) {
            return (array) $value->jsonSerialize();
        } elseif ($value instanceof \Traversable) {
            return iterator_to_array($value);
        }

        return (array) $value;
    }

    /**
     * Check if value can be an array
     *
     * @param mixed $value
     * @return bool
     */
    public static function canBeArray($value): bool
    {
        if (
            in_array(gettype($value), ['boolean', 'integer', 'double', 'string', 'array'])
            || $value instanceof Model
            || $value instanceof Enumerable
            || $value instanceof Arrayable
            || $value instanceof Jsonable
            || $value instanceof \JsonSerializable
            || $value instanceof \Traversable
        ) {
            return true;
        }

        return false;
    }

    /**
     * Randomize positions of an array
     *
     * @param array $array
     * @return array
     */
    public static function randomized($array): array
    {
        if (!is_array($array)) {
            $array = static::toArray($array);
        }

        if (!$array) {
            return [];
        }

        $keys = array_keys($array);
        shuffle($keys);
        $return = [];

        foreach ($keys as $index) {
            $return[$index] = $array[$index];
        }

        return $return;
    }
}
