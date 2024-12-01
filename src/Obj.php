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


    /**
     * Determine if the given invokable allows guests.
     *
     * @param callable|object|string $invokeable
     * @return bool
     */
    public static function allowsGuests($invokeable, $method = null)
    {
        if (is_string($invokeable) || is_object($invokeable)) {
            return static::methodAllowsGuests($invokeable, $method ?: '__construct');
        }

        if (is_callable($invokeable)) {
            return static::callbackAllowsGuests($invokeable);
        }

        return false;
    }
    /**
     * Determine if the given class method allows guests.
     *
     * @param string $class
     * @param string $method
     * @return bool
     */
    public static function methodAllowsGuests($class, $method)
    {
        try {
            $method = (new \ReflectionClass($class))->getMethod($method);
        } catch (\Exception) {
            return false;
        }

        if ($method) {
            $parameters = $method->getParameters();
            return isset($parameters[0]) && static::parameterAllowsGuests($parameters[0]);
        }

        return false;
    }

    /**
     * Determine if the given parameter allows guests.
     *
     * @param \ReflectionParameter $parameter
     * @return bool
     */
    public static function parameterAllowsGuests($parameter)
    {
        return ($parameter->hasType() && $parameter->allowsNull()) ||
               ($parameter->isDefaultValueAvailable() && is_null($parameter->getDefaultValue()));
    }

    /**
     * Determine if the callback allows guests.
     *
     * @param callable $callback
     * @return bool
     */
    public static function callbackAllowsGuests($callback)
    {
        try {
            $parameters = (new \ReflectionFunction($callback))->getParameters();

            return isset($parameters[0]) && static::parameterAllowsGuests($parameters[0]);
        } catch (\Exception) {
            return false;
        }
    }
}
