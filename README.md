# Реализация перечисляемого типа (enum) на PHP

Абстрактный класс `Enum` позволяет создавать enum-объекты (см. [перечисляемый тип](https://ru.wikipedia.org/wiki/Перечисляемый_тип)).

- Поддержка [дополнительных данных](#extradata) для значений.
- Поддержка [геттеров](#getters).
- Поддержка [фильтрации](#filtering). 
- Вспомогательные функции ([`toIds`](#toIds), [`toList`](#toList), [`toArray`](#toArray), [`toObjects`](#toObjects), [`isValid`](#isValid)).

## Установка

Рекомендуется установка через [composer](http://getcomposer.org/download/):

```
composer require vjik/php-enum
```

## Определение класса

### Вариант 1. Базовый

```php
use vjik\enum\Enum;

class Status extends Enum
{
    const DRAFT = 'draft';
    const PUBLISH = 'publish';
}
```

### Вариант 2. С именами

Массив с данными задаётся в статической функции `items()`.

```php
use vjik\enum\Enum;

class Status extends Enum
{
    const DRAFT = 'draft';
    const PUBLISH = 'publish';
    
    public static function items()
    {
        return [
            self::DRAFT => 'Черновик',
            self::PUBLISH => 'Опубликован',
        ];
    }
}
```

### Вариант 3. <a name="extradata"></a>С дополнительными данными

Для всех дополнительных данных необходимо прописать свойства `proteсted`.

```php
use vjik\enum\Enum;

class Status extends Enum
{
    const DRAFT = 'draft';
    const PUBLISH = 'publish';
    
    protected $priority;
    
    public static function items()
    {
        return [
            self::DRAFT => [
                'name' => 'Черновик',
                'priority' => -10,
            ],
            self::PUBLISH => [
                'name' => 'Опубликован',
                'priority' => 20,
            ]
        ];
    }
}
```

## Создание объекта

Создать объект можно через создание класса или статическую функцию `get`:

```php
$status = new Status(Status::DRAFT);
$status = Status::get(Status::DRAFT);
```

Второй вариант препочтительнее, так как он кэширует объекты и, если объект уже был инициализирован,
то он будет взят из кэша, а не будет создан заново.

## <a name="toIds"></a>Список значений `toIds`

Возвращает массив значений объекта. Поддерживает [фильтрацию](#filtering). 

```php
Status::toIds(); // ['draft', 'publish']
Status::toIds(['priority' => 20]); // ['publish']
```

## <a name="toList"></a>Список с названиями `toList`

Возвращает массив вида `$id => $name`. Поддерживает [фильтрацию](#filtering).

```php
Status::toList(); // ['draft' => 'Черновик', 'publish' => 'Опубликован']
Status::toList(['priority' => 20]); // ['publish' => 'Опубликован']
```

## <a name="toArray"></a>Массив с данными `toArray`
 
Возвращает массив вида:

```php
[
    $id => [
        'id' => $id,
        'name' => $name,
        'param1' => $param1,
        'param2' => $param2,
        …
    ],
    …
]
```

Поддерживает [фильтрацию](#filtering).

```php
Status::toArray();
Status::toArray(['priority' => 20]); // ['publish' => 'Опубликован']
```

## <a name="toObjects"></a>Массив объектов `toObjects`

Возвращает массив вида:

```php
[
    $id => Enum,
    …
]
```

## <a name="isValid"></a>Проверка значения `isValid`

Проверяет, существует ли значение в перечисляемом типе. Поддерживает [фильтрацию](#filtering).

```php
Status::isValid('new'); // false
Status::isValid('publish'); // true
Status::isValid('publish', [['<', 'priority', 5]]); // false
```

## <a name="filtering"></a>Фильтрация 

Методы [`toIds`](#toIds), [`toList`](#toList), [`toArray`](#toArray), [`toObjects`](#toObjects), [`isValid`](#isValid) поддерживают фильтрацию. 

Фильтр передаётся в виде массива:

```php
[
    $key => $value,
    [$operator, $key, $value],
    …
]
```

Поддерживаемые операторы: `=`, `!=`, `>`, `<`, `>=`, `<=`, `in`.

В качестве оператора можно использовать статическую функцию объекта. В функцию будут переданые все элементы массива за исключением оператора. Например:

```php
// Фильтр
[['numberMore', 102]]

// Функция в объекте
public static function numberMore($item, $v)
{
    return $item['number'] > $v;
}
```

### Оператор `in`

Проверяет, что значение соответствует одному из значений, указанных в массиве `$value`. Например:

```php
[
   Status::isValid('publish', [['in', 'priority', [5, 10]]]); 
   Status::isValid('closed', [['in', 'id', ['publish', 'closed', 'draft']]]); 
]
```

## <a name="getters"></a>Геттеры

Геттер — это метод, чьё название начинается со слова `get`. Часть названия после `get` определяет имя свойства.
Например, геттер `getAbsPriority` определяет свойство `absPriority`, как показано в коде ниже:

```php
use vjik\enum\Enum;

class Status extends Enum
{
    const DRAFT = 'draft';
    const PUBLISH = 'publish';
    
    protected $priority;
    
    protected function getAbsPriority()
    {
        return abs($this->priority);
    }
    
    public static function items()
    {
        return [
            self::DRAFT => [
                'name' => Черновик',
                'priority' => -10,
            ],
            self::PUBLISH => [
                'name' => 'Опубликован',
                'priority' => 20,
            ]
        ];
    }
}

$status = new Status(Status::DRAFT);
echo $status->absPriority; // 10
```

## Преобразование в строку

Объект поддерживает преобразование в строку (магический метод `__toString`). Возвращается значение в виде строки.

```php
$status = new Status(Status::DRAFT);
echo $status; // draft
```