<?php

namespace Attla\Support\Interfaces;

interface GraspableTypes
{
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
