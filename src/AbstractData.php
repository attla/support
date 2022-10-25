<?php

namespace Attla\Support;

use Illuminate\Contracts\Support\{
    Arrayable,
    Jsonable
};
use Illuminate\Support\Str as LaravelStr;

class AbstractData extends \ArrayObject implements
    Arrayable,
    Jsonable,
    \JsonSerializable
{
    use Traits\HasMagicAttributes;
    use Traits\HasArrayOffsets;

    /**
     * Store property values
     *
     * @var array
     */
    protected $dtoData = [];

    /**
     * Store dto methods methods.
     *
     * @var string[]
     */
    protected $mutators = [];

    /**
     * Store property values to ignore
     *
     * @var string[]
     */
    protected $dtoIgnore = [
        'dtoData',
        'dtoIgnore',
        'mutators',
    ];

    /**
     * Get all properties from object
     *
     * @param object $object
     * @return array
     */
    private function getProperties(object $object)
    {
        $properties = [];
        $reflection = new \ReflectionObject($object);

        do {
            $properties = array_merge($properties, $reflection->getProperties());
        } while ($reflection = $reflection->getParentClass());

        foreach ($properties as $key => $property) {
            $property->setAccessible(true);
            $properties[$property->getName()] = $property->isInitialized($object)
                ? $property->getValue($object)
                : null;
            unset($properties[$key]);
        }

        return $properties;
    }

    /**
     * Initialize the properties of abstract data
     *
     * @param object|array $data
     * @return void
     */
    protected function initializeProperties(object|array $source = [])
    {
        $defaultProperties = $this->getProperties($this);
        $this->mutators = Obj::getNames(Obj::getPublicMethods(static::class));

        foreach ($defaultProperties as $property => $value) {
            if (!in_array($property, $this->dtoIgnore)) {
                unset($this->{$property});
            }
        }

        foreach (
            array_merge(
                $defaultProperties,
                $this->getProperties((object) Arr::toArray($source))
            ) as $name => $value
        ) {
            if (
                !is_numeric($name)
                && !in_array($name, $this->dtoIgnore)
            ) {
                $this->set($name, $value);
            }
        }
    }

    /**
     * Get an attribute value
     *
     * @param string $name
     * @param mixed $default
     * @return mixed|null
     */
    public function get(string $name, $default = null)
    {
        $originalName = $name;

        $name = Str::removePrefix($name, 'get');

        $value = $this->dtoData[$name] ?? $this->dtoData[$originalName] ?? null;

        if ($this->hasMethod($getter = 'get' . LaravelStr::studly($name))) {
            return $this->{$getter}($value);
        }

        return $value ?? $default;
    }

    /**
     * Check if an attribute is set
     *
     * @param string $name
     * @return bool
     */
    protected function isset(string $name): bool
    {
        return key_exists($name, $this->dtoData)
            || $this->hasMethod('get' . LaravelStr::studly(
                $name = Str::removePrefix($name, 'get', 'set')
            )) || key_exists($name, $this->dtoData);
    }

    /**
     * Set an attribute value
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set(string $name, $value): void
    {
        $setterValue = $undefined = '#!@undefined@!#';

        $name = Str::removePrefix($name, 'set');

        if ($this->hasMethod($setter = 'set' . LaravelStr::studly($name))) {
            $setterValue = $this->{$setter}($value);
        }

        $this->dtoData[$name] = $setterValue !== $undefined
            ? $setterValue
            : $value;
    }

    /**
     * Unset an attribute
     *
     * @param string $name
     * @return void
     */
    protected function remove(string $name): void
    {
        if ($this->isset($name)) {
            unset($this->dtoData[$name]);
        }
    }

    /**
     * Fill this object with values given in associative array
     *
     * @param mixed[] $array
     * @return void
     */
    public function hydrate(array $array): void
    {
        foreach ($array as $key => $value) {
            if (!is_numeric($key)) {
                $this->set($key, $value);
            }
        }
    }

    /**
     * Extracts this object into associative array
     *
     * @return mixed[]
     */
    public function extract(): array
    {
        $data = [];
        foreach (array_keys($this->dtoData) as $name) {
            $data[$name] = $this->get($name);
        }

        return $data;
    }

    /**
     * Determine if the method exists
     *
     * @param string $name
     * @return bool
     */
    public function hasMethod(string $name): bool
    {
        return in_array($name, $this->mutators);
    }

    /**
     * Check if a attribute exists
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->isset($name);
    }

    /**
     * Create new instance from other source
     *
     * @param object|array $source
     * @return static
     */
    public static function from(object|array $source): static
    {
        return new static($source);
    }

    /**
     * Get values
     *
     * @return mixed[]
     */
    public function values(): array
    {
        return $this->extract();
    }

    /**
     * Get all values
     *
     * @return mixed[]
     */
    public function all(): array
    {
        return $this->extract();
    }

    /**
     * Transform the data into an array
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        return $this->extract();
    }

    /**
     * Get the array that should be JSON serialized
     *
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return $this->extract();
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
     * Create a new DTO instance
     *
     * @param object|array $source
     * @return void
     */
    public function __construct(object|array $source = [])
    {
        $this->initializeProperties($source);
    }

    /**
     * Dynamically retrieve or set the value
     *
     * @param string $name
     * @param array $args
     * @return mixed|void
     */
    public function __call($name, array $args)
    {
        if ($this->isset($name) && empty($args)) {
            return $this->get($name);
        }

        $this->set($name, ...$args);
    }
}
