<?php

namespace Attla\Support;

use Illuminate\Support\Arr as LaravelArr;

class Generic
{
    /**
     * Sort array or string based on seed.
     *
     * @param array|string $data
     * @param integer|null $seed
     * @return array|string
     */
    public static function sortBySeed(array|string $data, int|null $seed = null): array|string
    {
        if (is_null($seed)) {
            return $data;
        }

        mt_srand($seed);

        $isArray = is_array($data);
        $sorted = !$isArray ? str_split($data) : $data;

        $size = count($sorted);
        array_multisort(array_map(fn () => mt_rand(), range(1, $size)), $sorted);

        return $isArray == 'array' ? $sorted : implode('', $sorted);
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
}
