<?php

namespace Attla\Support\Laravel;

use Attla\Support\{
    Arr,
    Generic,
    Str
};
use Illuminate\Support\{
    Arr as LaravelArr,
    ServiceProvider as BaseServiceProvider,
    Str as LaravelStr,
    Stringable,
};

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider
     *
     * @return void
     */
    public function register()
    {
        Extend::from(Arr::class)
            ->to(LaravelArr::class)
            ->methods(
                'toArray',
                'canBeArray',
                'randomized',
            )->add();

        Extend::from(Str::class)
            ->to(LaravelStr::class)
            ->to(Stringable::class, Extend::CALLABLE)
            ->methods(
                'isBase64',
                'strlenBase64',
                'isBinary',
                'isHttpQuery',
                'isSerialized',
                'isHex',
                'removePrefix',
                'multiByteSplit',
            )->add();

        Extend::from(Generic::class)
            ->to(LaravelArr::class)
            ->to(LaravelStr::class)
            ->to(Stringable::class, Extend::CALLABLE)
            ->methods(
                'sortBySeed',
                'isUnique',
                'toInt',
            )->add();
    }
}
