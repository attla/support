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
        return Attempt::defined(static::get($key, Attempt::UNDEFINED));
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

        return Attempt::resolve(fn() => env($key, $default))
            ->or(fn() => config($key, $default))
            ->default($default)
            ->get();
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
        Attempt::resolve(fn() => config()->set($key, $value))
            ->or(fn() => static::memory()->set($key, $value));
    }
}
