<?php

namespace Attla\Support;

use Illuminate\Support\Arr as LaravelArr;

class Generic
{
    public static function sortBySeed(array|string $value, int $seed = null): array|string
    {
        $isArray = is_array($value);

        if (is_null($seed) || $isArray && LaravelArr::isAssoc($value)) {
            return $value;
        }

        mt_srand($seed);

        !$isArray && $value = str_split($value);

        $size = count($value);
        array_multisort(array_map(fn () => mt_rand(), range(1, $size)), $value);

        return $isArray == 'array' ? $value : implode('', $value);
    }
}
