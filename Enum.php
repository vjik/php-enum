<?php

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

    /**
     * @var array
     */
    protected static $_cache = [];

    /**
     * @var int|string
     */
    protected $id;

    /**
     * @var string
     */
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
     * Проверяет входит ли значение в допустимые
     * @param int|string $id
     * @param array $filter
     * @return bool
     * @throws ReflectionException
     */
    public static function isValid($id, array $filter = [])
    {
        return in_array($id, static::toIds($filter), true);
    }

    /**
     * Все доступные значения в виде массива с данными
     * @param array $filter ['key' => 'value', ['operator', 'key', 'value'], …]
     * @return array enum-значение - ключ, массив с данными - значение
     * @throws ReflectionException
     */
    public static function toArray(array $filter = [])
    {
        $class = get_called_class();
        if (!array_key_exists($class, static::$_cache)) {
            $reflection = new \ReflectionClass($class);
            if (is_callable([$class, 'items'])) {
                /** @noinspection PhpUndefinedMethodInspection */
                $items = $class::items();
                array_walk($items, function (&$item) {
                    $item = is_array($item) ? $item : ['name' => $item];
                });
            } else {
                $items = array_fill_keys($reflection->getConstants(), []);
            }
            foreach ($reflection->getConstants() as $constant) {
                if (!isset($items[$constant]['name'])) {
                    $items[$constant]['name'] = $constant;
                }
                $items[$constant]['id'] = $constant;
            }
            static::$_cache[$class] = $items;
        }
        $items = array_filter(static::$_cache[$class], function ($item) use ($filter) {
            foreach ($filter as $key => $filterItem) {
                if (is_int($key)) {
                    list($operator, $key, $value) = $filterItem;
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
                    $value = $filterItem;
                    if ($item[$key] != $value) {
                        return false;
                    }
                }
            }
            return true;
        });
        return $items;
    }

    /**
     * Все доступные значения в виде массива
     * @param array $filter
     * @return array
     * @throws ReflectionException
     */
    public static function toIds(array $filter = [])
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
    public static function toList(array $filter = [])
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
    public static function toObjects(array $filter = [])
    {
        $objects = [];
        foreach (static::toIds($filter) as $id) {
            $objects[$id] = new static($id);
        }
        return $objects;
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

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id;
    }
}
