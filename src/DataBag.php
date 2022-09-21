<?php

namespace Attla\Support;

use Illuminate\Contracts\Support\{
    Arrayable,
    Jsonable
};
use Illuminate\Support\Collection;

class DataBag extends \ArrayObject implements
    Interfaces\Baggable,
    Arrayable,
    Jsonable,
    \JsonSerializable
{
    use Traits\HasMagicAttributes;
    use Traits\HasArrayOffsets;
    use Traits\HasTypeGetters;

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

    /**
     * Get the data as a collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collect(): Collection
    {
        return new Collection($this->all());
    }
}
