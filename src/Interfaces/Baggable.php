<?php

namespace Attla\Support\Interfaces;

interface Baggable
{
    /**
     * Get all the data.
     */
    public function all(): array;

    /**
     * Returns the data keys.
     */
    public function keys(): array;

    /**
     * Returns true if a data key is defined.
     */
    public function has(string $key): bool;

    /**
     * Adds data.
     */
    public function add(object|array $data): void;

    /**
     * Replaces the current data by a new set.
     */
    public function replace(object|array $data): void;

    /**
     * Get a value.
     */
    public function get(string $key, $default);

    /**
     * Removes a value.
     */
    public function remove(string $key): void;

    /**
     * Sets a value
     */
    public function set(string $key, $value): void;

    /**
     * Clears all data values.
     */
    public function clear(): void;
}
