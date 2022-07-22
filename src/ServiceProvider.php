<?php

namespace Attla\Support;

use Illuminate\Support\{
    Arr as LaravelArr,
    ServiceProvider as BaseServiceProvider,
    Str as LaravelStr,
    Stringable,
};

class ServiceProvider extends BaseServiceProvider
{
    protected $extends = [
        [
            'from' => Arr::class,
            'to' => [
                LaravelArr::class,
            ],
            'methods' => [
                'toArray',
                'randomized',
            ],
        ],
        [
            'from' => Str::class,
            'to' => [
                'spread' => LaravelStr::class,
                'this' => Stringable::class,
            ],
            'methods' => [
                'isBase64',
                'isHttpQuery',
                'isSerialized',
            ],
        ],
    ];

    /**
     * Register the service provider
     *
     * @return void
     */
    public function register()
    {
        SupRegister($this->extends);
    }
}

function SupRegister($extends = [])
{
    foreach ($extends as $extend) {
        foreach ($extend['to'] ?? [] as $argType => $toClass) {
            foreach ($extend['methods'] ?? [] as $method) {
                ($toClass)::{'macro'}(
                    $method,
                    fn (...$args) => $argType == 'spread'
                        ? ($extend['from'])::$method(...$args)
                        : ($extend['from'])::$method($this->value, ...$args)
                );
            }
        }
    }
}
