<?php

namespace vjik\enum;

/**
 * Abstract Enum class
 *
 * @property mixed $value
 * @property mixed $name
 */
abstract class Enum
{


    /**
     * @var array
     */
    protected static $_cache = [];


    /**
     * @var mixed
     */
    protected $value;


    /**
     * @var string
     */
    protected $name;


    /**
     * @param mixed $value
     *
     * @throws \UnexpectedValueException
     */
    public function __construct($value)
    {
        if (!static::isValid($value)) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . get_called_class());
        }
        foreach (static::toArray()[$value] as $k => $v) {
            $this->$k = $v;
        }
    }


    /**
     * Проверяет входит ли значение в допустимые
     *
     * @param $value
     * @param $filter
     *
     * @return bool
     */
    public static function isValid($value, array $filter = [])
    {
        return in_array($value, static::toValues($filter), true);
    }


    /**
     * Все доступные значения в виде массива с данными
     *
     * @param array $filter ['key' => 'value', ['operator', 'key', 'value'], …]
     *
     * @return array enum-значение - ключ, массив с данными - значение
     */
    public static function toArray(array $filter = [])
    {
        $class = get_called_class();
        if (!array_key_exists($class, static::$_cache)) {
            if (is_callable([$class, 'items'])) {
                /** @noinspection PhpUndefinedMethodInspection */
                $items = $class::items();
            } else {
                $items = [];
                $reflection = new \ReflectionClass($class);
                foreach ($reflection->getConstants() as $constant) {
                    $items[$constant] = $constant;
                }
            }
            array_walk($items, function (&$item, $value) {
                $item = is_array($item) ? $item : ['name' => $item];
                if (!isset($item['name'])) {
                    $item['name'] = $value;
                }
                $item['value'] = $value;
            });
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
     *
     * @param array $filter
     *
     * @return array
     */
    public static function toValues(array $filter = [])
    {
        return array_keys(static::toArray($filter));
    }


    /**
     * Все доступные значение с именами
     *
     * @param array $filter
     *
     * @return array enum-значение - ключ, имя - значение
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
     * @param $name
     *
     * @return mixed
     *
     * @throws \LogicException
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (property_exists($this, $name)) {
            return $this->{$name};
        }
        throw new \LogicException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }
}
