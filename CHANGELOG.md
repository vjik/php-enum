# PHP Enum Implementation Change Log

## 4.0.0 May 27, 2021

Implement ideas from [RFC Enumerations](https://wiki.php.net/rfc/enumerations):

- New: Add protected method `match()`.
- New: Add factory method `tryFrom()`.
- New: Add method `getName()`.
- Chg: Remove immutability objects.
- Chg: Rename methods `toObjects()` to `cases()` and `toValues()` to `values()`.
- Chg: Use private constants in enum object.
- Chg: On create object via method `from()` with invalid value throws `ValueError` instead `UnexpectedValueException`.

## 3.0.0 May 26, 2021

- Chg: Rewrite the package from scratch.

## 2.2.0 January 15, 2020

- New: Add support static methods of current object in filters.

## 2.1.0 February 26, 2018

- New: Add static method `get()` that returns object by its identifier.

## 2.0.0 September 4, 2017

- Chg: Property `$value` renamed to `$id`.
- Chg: Method `toValues()` renamed to `toIds()`.

## 1.2.0 August 29, 2017

- New: Add support operator `in` in filters.

## 1.1.1 August 29, 2017

- Bug: Fixed problem with values type casting to integer.

## 1.1.0 August 21, 2017

- New: Add method `toObjects()`.

## 1.0.0 July 15, 2017

- Initial release.
