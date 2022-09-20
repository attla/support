<?php

namespace Attla\Support\Traits;

trait HasArrayOffsets
{
    /**
     * Determine if the given offset exists
     *
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->isset($offset);
    }

    /**
     * Get the value for a given offset
     *
     * @param string $offset
     * @return mixed|null
     */
    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * Set the value at the given offset
     *
     * @param string $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * Unset the value at the given offset
     *
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }
}
