<?php

namespace Attla\Support;

class Attempt
{
    /**
     * Undefined identification.
     *
     * @var string
     */
    public const UNDEFINED = '!@#undefined#@!';
    private $value;
    private $default;

    /**
     * Create attempt instance from a callable result.
     *
     * @param callable $callable
     * @param mixed $default
     * @return $this
     */
    public static function resolve(callable $callback, $default = null)
    {
        $value = static::UNDEFINED;

        try {
            $value = $callback();
        } catch (\Exception | \Throwable $e) {
        }

        return new static($value, $default);
    }

    /**
     * Create a new Attempt instance
     *
     * @param mixed $value
     * @param mixed $default
     * @return void
     */
    public function __construct($value = null, $default = null)
    {
        $this->value = $value ?? static::UNDEFINED;
        $this->$default = $default ?? static::UNDEFINED;
    }

    /**
     * Get a value.
     *
     * @return mixed
     */
    public function get()
    {
        if (
            static::undefined($this->value)
            || is_null($this->value)
               && static::defined($this->default)
               && !is_null($this->default)
        ) {
            return $this->default;
        }

        return $this->value;
    }

    /**
     * Resolve other callable if previus are undefined.
     *
     * @param callable $callable
     * @return $this
     */
    public function or(callable $callback)
    {
        if (static::defined($this->value)) {
            return $this;
        }

        return static::resolve($callback, $this->default);
    }

    /**
     * Set default value.
     *
     * @param mixed $default
     * @return $this
     */
    public function default($default = null)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Check if value is defined.
     *
     * @param mixed $value
     * @return bool
     */
    public static function defined($value)
    {
        return static::UNDEFINED !== $value;
    }

    /**
     * Check if value is undefined.
     *
     * @param mixed $value
     * @return bool
     */
    public static function undefined($value)
    {
        return !static::defined($value);
    }

    /**
     * Tap a callable.
     *
     * @param callable $callback
     * @return mixed
     */
    public function tap(callable $callback = null)
    {
        return tap($this->get(), $callback);
    }
}
