<?php

namespace Attla\Support\Traits;

trait HasGraspableTypes
{
    /**
     * Returns the alphabetic characters of the data value.
     *
     * @param string $key
     * @param string $default
     * @return string
     */
    public function getAlpha(string $key, string $default = ''): string
    {
        return preg_replace('/[^[:alpha:]]/', '', $this->get($key, $default));
    }

    /**
     * Returns the alphabetic characters and digits of the data value.
     *
     * @param string $key
     * @param string $default
     * @return string
     */
    public function getAlnum(string $key, string $default = ''): string
    {
        return preg_replace('/[^[:alnum:]]/', '', $this->get($key, $default));
    }

    /**
     * Returns the digits of the data value.
     *
     * @param string $key
     * @param string $default
     * @return string
     */
    public function getDigits(string $key, string $default = ''): string
    {
        return str_replace(['-', '+'], '', $this->filter($key, $default, \FILTER_SANITIZE_NUMBER_INT));
    }

    /**
     * Returns the data value converted to integer.
     *
     * @param string $key
     * @param int $default
     * @return int
     */
    public function getInt(string $key, int $default = 0): int
    {
        return (int) $this->get($key, $default);
    }

    /**
     * Returns the data value converted to array.
     *
     * @param string $key
     * @param array $default
     * @return array
     */
    public function getArray(string $key, array $default = []): array
    {
        return (array) $this->get($key, $default);
    }

    /**
     * Returns the data value converted to boolean.
     *
     * @param string $key
     * @param bool $default
     * @return bool
     */
    public function getBoolean(string $key, bool $default = false): bool
    {
        return $this->filter($key, $default, \FILTER_VALIDATE_BOOL);
    }

    /**
     * Filter a key.
     *
     * @param int $filter FILTER_* constant
     *
     * @throws \InvalidArgumentException
     *
     * @see https://php.net/filter-var
     */
    public function filter(
        string $key,
        mixed $default = null,
        int $filter = \FILTER_DEFAULT,
        mixed $options = []
    ) {
        $value = $this->get($key, $default);

        // Always turn $options into an array - this allows filter_var option shortcuts.
        if (!is_array($options) && $options) {
            $options = ['flags' => $options];
        }

        // Add a convenience check for arrays.
        if (is_array($value) && !isset($options['flags'])) {
            $options['flags'] = \FILTER_REQUIRE_ARRAY;
        }

        if ((\FILTER_CALLBACK & $filter) && !(($options['options'] ?? null) instanceof \Closure)) {
            throw new \InvalidArgumentException(sprintf(
                'A Closure must be passed to "%s()" when FILTER_CALLBACK is used, "%s" given.',
                __METHOD__,
                get_debug_type($options['options'] ?? null)
            ));
        }

        return filter_var($value, $filter, $options);
    }
}
