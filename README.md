# PHP Enum Implementation

[![Latest Stable Version](https://poser.pugx.org/vjik/php-enum/v/stable.png)](https://packagist.org/packages/vjik/php-enum)
[![Total Downloads](https://poser.pugx.org/vjik/php-enum/downloads.png)](https://packagist.org/packages/vjik/php-enum)
[![Build status](https://github.com/vjik/php-enum/workflows/build/badge.svg)](https://github.com/vjik/php-enum/actions?query=workflow%3Abuild)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fvjik%2Fphp-enum%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/vjik/php-enum/master)
[![static analysis](https://github.com/vjik/php-enum/workflows/static%20analysis/badge.svg)](https://github.com/vjik/php-enum/actions?query=workflow%3A%22static+analysis%22)

The package provide abstract class `Enum` that intended to create
[enumerated objects](https://en.wikipedia.org/wiki/Enumerated_type) with support [extra data](#extradata) and 
auxiliary static functions [`toValues()`](#toList), [`toObjects()`](#toObjects) and [`isValid()`](#isValid).

## Requirements

- PHP 8.0 or higher.

## Installation

The package could be installed with composer:

```shell
composer require vjik/php-enum --prefer-dist
```

## General usage

### Declaration of class

```php
use Vjik\Enum\Enum;

/**
 * @method static self NEW()
 * @method static self PROCESS()
 * @method static self DONE()
 */
final class Status extends Enum
{
    public const NEW = 'new';
    public const PROCESS = 'process';
    public const DONE = 'done';
}
```

### Creating an object

#### By static method `from()`

```php
$process = Status::from('process');
```

#### By static method with a name identical to the constant name

Static methods are automatically implemented to provide quick access to an enum value.

```php
$process = Status::PROCESS();
```

### <a name="extradata"></a>Class with extra data

Set data in the protected static function `data()` and create getters using the protected method `getPropertyValue()`:

```php
use Vjik\Enum\Enum;

/**
 * @method static self CREATE()
 * @method static self UPDATE()
 */
final class Action extends Enum
{
    public const CREATE = 1;
    public const UPDATE = 2;

    protected static function data(): array
    {
        return [
            self::CREATE => [
                'tip' => 'Create document',
            ],
            self::UPDATE => [
                'tip' => 'Update document',
            ],
        ];
    }

    public function getTip(): string
    {
        /** @var string */
        return $this->getPropertyValue('tip');
    }
}
```

Usage:

```php
echo Action::CREATE()->getTip();
```

### Auxiliary static functions

#### <a name="toValues"></a> List of values `toValues()`

Returns array of pairs constant names and values.

```php
// ['CREATE' => 1, 'UPDATE' => 2]
Action::toValues(); 
```

#### <a name="toObjects"></a> List of objects `toObjects()`

Returns array of pairs constant names and objects:

```php
// ['CREATE' => $createObject, 'UPDATE' => $updateObject]
Action::toObjects();
```

#### <a name="isValid"></a> Validate value `isValid()`

Check if value is valid on the enum set.

```php
Action::isValid(1); // true
Action::isValid(99); // false
```

### Casting to string

`Enum` support casting to string (using magic method `__toString`). The value is returned as a string.

```php
echo Status::DONE(); // done
```

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework. To run it:

```shell
./vendor/bin/infection
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

## License

The PHP Enum implementation is free software. It is released under the terms of the BSD License. Please see [`LICENSE`](./LICENSE.md) for more information.

## Credits

Version 3 of this package is inspired by [`myclabs/php-enum`](https://github.com/myclabs/php-enum). 
