<?php

namespace Attla\Support;

use Illuminate\Support\Arr as LaravelArr;

class Generic
{
    private static $sorted = [];
    /**
     * Sort array or string based on seed.
     *
     * @param array|string $data
     * @param int|string|null $seed
     * @return array|string
     */
    public static function sortBySeed(array|string $data, int|string|null $seed = null): array|string
    {
        if (is_null($seed)) {
            return $data;
        }

        if (isset(static::$sorted[$key = sha1((is_array($data) ? json_encode($data) : $data) . $seed)])) {
            return static::$sorted[$key];
        }

        $isArray = is_array($data);
        $sorted = !$isArray ? Str::multiByteSplit($data) : $data;

        $size = count($sorted);
        mt_srand(static::toInt($seed));
        array_multisort(array_map(fn () => mt_rand(), range(1, $size)), $sorted);

        return static::$sorted[$key] = $isArray ? $sorted : implode('', $sorted);
    }

    /**
     * Determines if an array or string is unique.
     *
     * @param array|string $data
     * @return bool
     */
    public static function isUnique(array|string $data): bool
    {
        $array = is_array($data) ? $data : str_split($data);
        return count(array_unique($array)) == count($array);
    }

    /**
     * Convert an array or string to integer.
     *
     * @param array|string $data
     * @return int
     */
    public static function toInt($data): int
    {
        return is_int($data) ? $data : (int) preg_replace('[\D]', '', substr(md5(json_encode($data)), 0, 16));
    }
}
