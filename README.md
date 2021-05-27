# PHP Enum Implementation

[![Latest Stable Version](https://poser.pugx.org/vjik/php-enum/v/stable.png)](https://packagist.org/packages/vjik/php-enum)
[![Total Downloads](https://poser.pugx.org/vjik/php-enum/downloads.png)](https://packagist.org/packages/vjik/php-enum)
[![Build status](https://github.com/vjik/php-enum/workflows/build/badge.svg)](https://github.com/vjik/php-enum/actions?query=workflow%3Abuild)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fvjik%2Fphp-enum%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/vjik/php-enum/master)
[![static analysis](https://github.com/vjik/php-enum/workflows/static%20analysis/badge.svg)](https://github.com/vjik/php-enum/actions?query=workflow%3A%22static+analysis%22)

The package provide abstract class `Enum` that intended to create
[enumerated objects](https://en.wikipedia.org/wiki/Enumerated_type) with support [extra data](#extradata) and auxiliary static functions [`values()`](#values), [`cases()`](#cases) and [`isValid()`](#isValid).

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
    private const NEW = 'new';
    private const PROCESS = 'process';
    private const DONE = 'done';
}
```

### Creating an object

#### By static method `from()`

```php
$process = Status::from('process');
```

On create object with invalid value throws `ValueError`.

#### By static method `tryFrom()`

```php
$process = Status::tryFrom('process'); // Status object with value "process"
$process = Status::tryFrom('not-exists'); // null
```

On create object with invalid value returns `null`.

#### By static method with a name identical to the constant name

Static methods are automatically implemented to provide quick access to an enum value.

```php
$process = Status::PROCESS();
```

### Getting value and name

```php
Status::DONE()->getName(); // DONE
Status::DONE()->getValue(); // done
```

### <a name="extradata"></a>Class with extra data

Set data in the protected static function `data()` and create getters using the protected method `getPropertyValue()`.
Also you can create getter using protected method `match()`.

```php
use Vjik\Enum\Enum;

/**
 * @method static self CREATE()
 * @method static self UPDATE()
 */
final class Action extends Enum
{
    private const CREATE = 1;
    private const UPDATE = 2;

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
    
    public function getColor(): string
    {
        return $this->match([
            self::CREATE => 'red',
            self::UPDATE => 'blue',
        ]);
    }
    
    public function getCode(): int
    {
        return $this->match([
            self::CREATE => 1,
        ], 99);
    }
}
```

Usage:

```php
echo Action::CREATE()->getTip();
echo Action::CREATE()->getColor();
echo Action::CREATE()->getCode();
```

### Auxiliary static functions

#### <a name="values"></a> List of values `values()`

Returns list of values.

```php
// [1, 2]
Action::values(); 
```

#### <a name="cases"></a> List of objects `cases()`

Returns list of objects:

```php
// [$createObject, $updateObject]
Action::cases();
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
