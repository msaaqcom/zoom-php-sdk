<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionNamedType;
use ReflectionProperty;
use UnitEnum;

class Model
{
    protected array $casts;

    public function __construct(public array $__response = [])
    {
        foreach ($__response as $property => $value) {
            if (! property_exists($this, $property)) {
                continue;
            }

            $method = Str::of($property)->prepend('set_')->camel()->prepend('__')->toString();
            if (method_exists($this, $method)) {
                $this->$property = $this->$method($value);
            } elseif ($enum = $this->isEnum($property)) {
                if (is_string($value) && is_numeric($value)) {
                    $value = (int)$value;
                }

                $this->$property = $enum::tryFrom($value);
            } elseif ($class = $this->isModel($property)) {
                $this->$property = new $class($value);
            } else {
                $this->$property = $this->coerce($property, $value);
            }
        }
    }

    public function __get(string $name)
    {
        echo "Getting '$name'\n";
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): '.$name.
            ' in '.$trace[0]['file'].
            ' on line '.$trace[0]['line'],
            E_USER_NOTICE);

        return null;
    }

    /**
     * Coerce a raw API value to the property's declared scalar type.
     *
     * Zoom is inconsistent about whether numeric identifiers come back as JSON numbers or as
     * quoted strings, and some identifiers (registrant ids) are not numeric at all. Assigning
     * raw therefore throws a TypeError for the exact same field depending on the endpoint and
     * the value — and because TypeError extends Error rather than Exception, callers that guard
     * with catch (Exception) do not see it, losing work that the API has already performed.
     */
    private function coerce(string $property, mixed $value): mixed
    {
        $type = $this->getPropertyType($property);

        if ($type === null || $value === null || ! is_scalar($value)) {
            return $value;
        }

        return match ($type) {
            'int' => is_numeric($value) ? (int) $value : $value,
            'float' => is_numeric($value) ? (float) $value : $value,
            'string' => (string) $value,
            'bool' => (bool) $value,
            default => $value,
        };
    }

    /**
     * The property's declared type name, or null when it is absent or a union.
     *
     * A union (string|int) accepts the raw value as-is, so there is nothing to coerce and
     * nothing to resolve to an enum or nested model.
     */
    private function getPropertyType(string $property): ?string
    {
        $type = (new ReflectionProperty($this, $property))->getType();

        return $type instanceof ReflectionNamedType ? $type->getName() : null;
    }

    private function isEnum(string $property): bool|string
    {
        if (! $type = $this->getPropertyType($property)) {
            return false;
        }

        return enum_exists($type) ? $type : false;
    }

    private function isModel(string $property): bool|string
    {
        if (! $type = $this->getPropertyType($property)) {
            return false;
        }

        if (! class_exists($type)) {
            return false;
        }

        return is_subclass_of($type, Model::class) ? $type : false;
    }

    public function toArray(): array
    {
        $array = Arr::except(get_object_vars($this), ['__response']);

        foreach ($array as $key => $value) {
            if ($value instanceof UnitEnum) {
                $array[$key] = $value->value;
            } elseif ($value instanceof Model) {
                $array[$key] = $value->toArray();
            }
        }

        return $array;
    }
}
