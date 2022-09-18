<?php

namespace Attla\Support\Interfaces;

interface BagInterface
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

    /**
     * Returns the alphabetic characters of the data value.
     */
    public function getAlpha(string $key, string $default): string;

    /**
     * Returns the alphabetic characters and digits of the data value.
     */
    public function getAlnum(string $key, string $default): string;

    /**
     * Returns the digits of the data value.
     */
    public function getDigits(string $key, string $default): string;

    /**
     * Returns the data value converted to integer.
     */
    public function getInt(string $key, int $default): int;

    /**
     * Returns the data value converted to boolean.
     */
    public function getBoolean(string $key, bool $default): bool;

    /**
     * Filter a key.
     */
    public function filter(string $key, $default, int $filter, $options);
}
