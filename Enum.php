<?php

declare(strict_types=1);

namespace vjik\enum;

use LogicException;
use ReflectionException;
use UnexpectedValueException;

/**
 * Abstract Enum class
 * @property mixed $id
 * @property mixed $name
 */
abstract class Enum
{
    protected static array $cacheData = [];
    protected static array $cacheInstances = [];

    /**
     * @var int|string
     */
    protected $id;

    protected $name;

    /**
     * @param int|string $id
     * @throws UnexpectedValueException
     * @throws ReflectionException
     */
    public function __construct($id)
    {
        if (!static::isValid($id)) {
            throw new UnexpectedValueException("Value '$id' is not part of the enum " . get_called_class());
        }
        foreach (static::toArray()[$id] as $k => $v) {
            $this->$k = $v;
        }
    }

    /**
     * @param int|string $id
     * @return static
     * @throws ReflectionException
     * @throws UnexpectedValueException
     * @since 2.1.0
     */
    public static function get($id): self
    {
        $key = get_called_class() . '~' . $id;
        if (empty(static::$cacheInstances[$key])) {
            static::$cacheInstances[$key] = new static($id);
        }
        return static::$cacheInstances[$key];
    }

    /**
     * Проверяет входит ли значение в допустимые
     * @param int|string $id
     * @param array $filter
     * @return bool
     * @throws ReflectionException
     */
    public static function isValid($id, array $filter = []): bool
    {
        return in_array($id, static::toIds($filter), true);
    }

    /**
     * Все доступные значения в виде массива с данными
     * @param array $filter ['key' => 'value', ['operator', 'key', 'value'], …]
     * @return array enum-значение - ключ, массив с данными - значение
     * @throws ReflectionException
     */
    public static function toArray(array $filter = []): array
    {
        $items = array_map(function (array $data) {
            return $data['item'];
        }, static::getData());
        return array_filter($items, function ($item) use ($filter) {
            foreach ($filter as $key => $filterItem) {
                if (is_int($key)) {
                    $operator = $filterItem[0];
                    if (in_array($operator, ['=', '!=', '>', '<', '>=', '<=', 'in'])) {
                        $key = $filterItem[1];
                        $value = $filterItem[2];
                        switch ($operator) {
                            case '=':
                                if ($item[$key] != $value) {
                                    return false;
                                }
                                break;

                            case '!=':
                                if ($item[$key] == $value) {
                                    return false;
                                }
                                break;

                            case '>':
                                if ($item[$key] <= $value) {
                                    return false;
                                }
                                break;

                            case '<':
                                if ($item[$key] >= $value) {
                                    return false;
                                }
                                break;

                            case '>=':
                                if ($item[$key] < $value) {
                                    return false;
                                }
                                break;

                            case '<=':
                                if ($item[$key] > $value) {
                                    return false;
                                }
                                break;

                            case 'in':
                                return in_array($item[$key], $value, true);
                                break;
                        }
                    } else {
                        return call_user_func_array(
                            [get_called_class(), $operator],
                            array_merge([$item], array_slice($filterItem, 1))
                        );
                    }
                } else {
                    $value = $filterItem;
                    if ($item[$key] != $value) {
                        return false;
                    }
                }
            }
            return true;
        });
    }

    /**
     * Все доступные значения в виде массива
     * @param array $filter
     * @return array
     * @throws ReflectionException
     */
    public static function toIds(array $filter = []): array
    {
        $ids = [];
        foreach (static::toArray($filter) as $item) {
            $ids[] = $item['id'];
        }
        return $ids;
    }

    /**
     * Все доступные значение с именами
     * @param array $filter
     * @return array enum-значение - ключ, имя - значение
     * @throws ReflectionException
     */
    public static function toList(array $filter = []): array
    {
        $list = [];
        foreach (static::toArray($filter) as $id => $data) {
            $list[$id] = $data['name'];
        }
        return $list;
    }

    /**
     * Все доступные значения в виде объектов
     * @param array $filter
     * @return array
     * @throws ReflectionException
     */
    public static function toObjects(array $filter = []): array
    {
        $objects = [];
        foreach (static::toIds($filter) as $id) {
            $objects[$id] = static::get($id);
        }
        return $objects;
    }

    public static function __callStatic($name, $arguments)
    {
        if ($name === 'items') {
            return [];
        }

        foreach (static::getData() as $id => $data) {
            if ($data['constantName'] === $name) {
                return static::get($id);
            }
        }

        throw new \RuntimeException();
    }

    /**
     * @param string $name
     * @return mixed
     * @throws LogicException
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (property_exists($this, $name)) {
            return $this->{$name};
        }
        throw new LogicException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }

    private static function getData(): array
    {
        $class = get_called_class();
        if (!array_key_exists($class, static::$cacheData)) {
            $reflection = new \ReflectionClass($class);

            $items = call_user_func([$class, 'items']);

            $data = [];
            foreach ($reflection->getConstants() as $constantName => $id) {
                if (array_key_exists($id, $items)) {
                    $item = is_array($items[$id]) ? $items[$id] : [
                        'name' => $items[$id],
                    ];
                } else {
                    $item = [];
                }

                $item['id'] = $id;
                if (!array_key_exists('name', $item)) {
                    $item['name'] = $id;
                }

                $data[$id] = [
                    'constantName' => $constantName,
                    'item' => $item,
                ];
            }

            static::$cacheData[$class] = $data;
        }
        return static::$cacheData[$class];
    }
}
