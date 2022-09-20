<?php

namespace Attla\Support\Traits;

trait HasMagicAttributes
{
    /**
     * Dynamically retrieve the value a attribute.
     *
     * @param string $key
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Dynamically set the value a attribute.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Dynamically check if a attribute is defined.
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->isset($key);
    }

    /**
     * Dynamically unset a attribute.
     *
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {
        $this->remove($key);
    }
}
