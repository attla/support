<?php

namespace Attla\Support;

use Illuminate\Support\Arr as LaravelArr;

class Obj
{
    /**
     * Get defined public methods from object.
     *
     * @param object|string $object
     * @return string[]
     */
    public static function getPublicMethods(object|string $object): array
    {
        return static::notInherited(
            static::getAllPublicMethods($object),
            $object
        );
    }

    /**
     * Get all defined public methods from object.
     *
     * @param object|string $object
     * @return \ReflectionMethod[]
     */
    public static function getAllPublicMethods(object|string $object): array
    {
        return static::getMethods($object, \ReflectionMethod::IS_PUBLIC);
    }

    /**
     * Filter methods not inherited.
     *
     * @param \ReflectionMethod[] $methods
     * @param object|string $object
     * @return array
     */
    public static function notInherited(array $methods, object|string $class): array
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        return LaravelArr::where(
            $methods,
            fn($method) => $method->class == $class
        );
    }

    /**
     * Get methods names.
     *
     * @param \ReflectionMethod[] $methods
     * @return string[]
     */
    public static function getNames(array $methods): array
    {
        return LaravelArr::map($methods, fn($method) => $method->name);
    }

    /**
     * Get methods from object.
     *
     * @param object|string $object
     * @param int|null $visibility
     * @return \ReflectionMethod[]
     */
    public static function getMethods(object|string $object, int|null $visibility = null): array
    {
        return (new \ReflectionClass($object))->getMethods($visibility);
    }
}
