<?php

declare(strict_types=1);

namespace Vjik\Enum;

use BadMethodCallException;
use ReflectionClass;
use ReflectionClassConstant;
use ValueError;

use function constant;
use function defined;
use function in_array;

abstract class Enum
{
    private mixed $value;

    /**
     * @psalm-var array<class-string, array<string, mixed>>
     */
    private static array $cache = [];

    /**
     * @psalm-var array<class-string, array<string, static>>
     */
    private static array $instances = [];

    final protected function __construct(mixed $value)
    {
        if (!self::isValid($value)) {
            throw new ValueError("Value '$value' is not part of the enum " . static::class . '.');
        }

        $this->value = $value;
    }

    final public function getValue(): mixed
    {
        return $this->value;
    }

    final public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * @return static
     */
    final public static function from(mixed $value): self
    {
        return new static($value);
    }

    /**
     * @return static
     */
    final public static function __callStatic(string $name, array $arguments): self
    {
        $class = static::class;
        if (!isset(self::$instances[$class][$name])) {
            $constant = $class . '::' . $name;
            if (!defined($constant)) {
                $message = "No static method or enum constant '$name' in class " . static::class . '.';
                throw new BadMethodCallException($message);
            }
            return self::$instances[$class][$name] = new static(constant($constant));
        }
        return clone self::$instances[$class][$name];
    }

    final public static function toValues(): array
    {
        $class = static::class;

        if (!isset(static::$cache[$class])) {
            /** @psalm-suppress TooManyArguments Remove this after fix https://github.com/vimeo/psalm/issues/5837 */
            static::$cache[$class] = (new ReflectionClass($class))->getConstants(ReflectionClassConstant::IS_PUBLIC);
        }

        return static::$cache[$class];
    }

    /**
     * @return static[]
     */
    final public static function toObjects(): array
    {
        $class = static::class;

        $objects = [];
        /**
         * @var string $key
         * @var mixed $value
         */
        foreach (self::toValues() as $key => $value) {
            if (isset(self::$instances[$class][$key])) {
                $objects[$key] = clone self::$instances[$class][$key];
            } else {
                $objects[$key] = self::$instances[$class][$key] = new static($value);
            }
        }

        return $objects;
    }

    final public static function isValid(mixed $value): bool
    {
        return in_array($value, static::toValues(), true);
    }

    /**
     * @psalm-return array<array-key, array<string, mixed>>
     */
    protected static function data(): array
    {
        return [];
    }

    final protected function getPropertyValue(string $key, mixed $default = null): mixed
    {
        /** @psalm-suppress MixedArrayOffset */
        return static::data()[$this->value][$key] ?? $default;
    }
}
