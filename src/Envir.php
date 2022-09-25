<?php

namespace Attla\Support;

class Envir
{
    /**
     * Instance of DataBag.
     *
     * @var DataBag
     */
    private static $memory;

    /**
     * Undefined identification.
     *
     * @var string
     */
    private static $undefined = '!@#undefined#@!';

    /**
     * Try use a callable.
     *
     * @param callable $callback
     * @return mixed
     */
    private static function try(callable $callback)
    {
        $value = static::$undefined;

        try {
            $value = $callback();
        } catch (\Exception | \Throwable $e) {
        }

        return $value;
    }

    /**
     * Get the memory DataBag instance.
     *
     * @return DataBag
     */
    private static function memory()
    {
        if (is_null(static::$memory)) {
            return static::$memory = new DataBag();
        }

        return static::$memory;
    }

    /**
     * Returns true if a env key is defined.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return static::get($key, static::$undefined) !== static::$undefined;
    }

    /**
     * Get a value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        if (static::memory()->has($key)) {
            return static::memory()->get($key, $default);
        }

        $value = static::try(fn() => env($key, $default));
        $value === static::$undefined && $value = static::try(fn() => config($key, $default));

        return $value === static::$undefined ? $default : $value;
    }

    /**
     * Removes a value.
     *
     * @param string $key
     * @return void
     */
    public static function remove(string $key): void
    {
        static::set($key, null);
    }

    /**
     * Sets a value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        $void = static::try(fn() => config()->set($key, $value));
        !is_null($void) && static::memory()->set($key, $value);
    }
}
