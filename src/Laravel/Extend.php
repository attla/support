<?php

namespace Attla\Support\Laravel;

use Illuminate\Support\Arr as LaravelArr;

class Extend
{
    public const ACCESS_TYPES = [
        self::STATIC,
        self::CALLABLE,
    ];

    public const STATIC = 0;
    public const CALLABLE = 1;

    public string $from;
    public array $to;
    public array $methods = [];

    public function __construct(
        string $from = '',
        array $to = [],
        array $methods = [],
    ) {
        $this->from = $from;
        $this->to = $to;
        $this->methods = $methods;
    }

    public static function from(string $class): self
    {
        return new static($class);
    }

    public function to(string $class, int|null $access = null): self
    {
        $this->to[$class] = is_null($access) || !in_array($access, static::ACCESS_TYPES)
            ? static::STATIC
            : $access;

        return $this;
    }

    public function methods(...$methods): self
    {
        $this->methods = LaravelArr::where(LaravelArr::flatten($methods), fn($value) => is_string($value));
        return $this;
    }

    public function register()
    {
        registerSupportMacro($this);
    }
    public function add()
    {
        $this->register();
    }
}

if (!function_exists('registerSupportMacro')) {
    function registerSupportMacro(Extend $extend)
    {
        foreach ($extend->to ?? [] as $class => $access) {
            foreach ($extend->methods ?? [] as $method) {
                ($class)::{'macro'}(
                    $method,
                    fn (...$args) => $access == Extend::STATIC
                        ? ($extend->from)::$method(...$args)
                        : ($extend->from)::$method($this->value, ...$args)
                );
            }
        }
    }
}
