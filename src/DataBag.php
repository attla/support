<?php

namespace Attla\Support;

use Illuminate\Contracts\Support\{
    Arrayable,
    Jsonable
};

class DataBag extends \ArrayObject implements
    Interfaces\BagInterface,
    Arrayable,
    Jsonable,
    \JsonSerializable
{
    use Traits\HasMagicAttributes;
    use Traits\HasArrayOffsets;

    /**
     * Data storage.
     *
     * @var mixed[]
     */
    protected $data = [];

    /**
     * Create a new DataBag instance.
     *
     * @param object|array $data
     * @return void
     */
    public function __construct(object|array $data = [])
    {
        $this->data = Arr::toArray($data);
    }

    /**
     * Get all the data.
     *
     * @return mixed[]
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Returns the data keys.
     *
     * @return string[]
     */
    public function keys(): array
    {
        return array_keys($this->all());
    }

    /**
     * Returns true if a data key is defined.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->all());
    }

    /**
     * Adds data.
     *
     * @param object|array $data
     * @return void
     */
    public function add(object|array $data = []): void
    {
        $this->data = array_replace($this->data, Arr::toArray($data));
    }

    /**
     * Replaces the current data by a new set.
     *
     * @param object|array $data
     * @return void
     */
    public function replace(object|array $data = []): void
    {
        $this->data = Arr::toArray($data);
    }

    /**
     * Get a value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    /**
     * Removes a value.
     *
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }

    /**
     * Sets a value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Clears all data values.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->data = [];
    }

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

    /**
     * Get values
     *
     * @return mixed[]
     */
    public function values(): array
    {
        return $this->all();
    }

    /**
     * Transform the data into an array
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        return $this->all();
    }

    /**
     * Get the array that should be JSON serialized
     *
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return $this->all();
    }

    /**
     * Convert the data to JSON
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Returns an iterator for data values.
     *
     * @return \ArrayIterator<string, mixed>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * Returns the number of data values.
     */
    public function count(): int
    {
        return count($this->all());
    }
}
