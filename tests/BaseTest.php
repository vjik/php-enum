<?php

declare(strict_types=1);

namespace Vjik\Enum\Tests;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;
use Vjik\Enum\Tests\Support\Pure;
use Vjik\Enum\Tests\Support\WithData;

final class BaseTest extends TestCase
{
    public function testCreateViaFrom(): void
    {
        $foo = Pure::from(Pure::FOO);
        self::assertSame(Pure::FOO, $foo->getValue());
    }

    public function testCreateViaFunction(): void
    {
        $foo = Pure::FOO();
        self::assertSame(Pure::FOO, $foo->getValue());
    }

    public function testImmutabilityCreateViaFunction(): void
    {
        self::assertNotSame(Pure::FOO(), Pure::FOO());
    }

    public function testCreateWithInvalidValue(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Value \'9\' is not part of the enum Vjik\Enum\Tests\Support\Pure.');
        Pure::from(9);
    }

    public function testCreateWithInvalidFunction(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            'No static method or enum constant \'NOT_EXISTS\' in class Vjik\Enum\Tests\Support\Pure.'
        );
        Pure::NOT_EXISTS();
    }

    public function testToString(): void
    {
        self::assertSame('foo', (string)Pure::FOO());
        self::assertSame('1', (string)Pure::ONE());
    }

    public function testGetData(): void
    {
        $one = WithData::ONE();
        self::assertSame('One', $one->getName());
    }

    public function testGetNotExistsData(): void
    {
        $three = WithData::THREE();
        self::assertNull($three->getName());
    }

    public function testGetDataWithoutData(): void
    {
        $two = Pure::TWO();
        self::assertNull($two->getName());
    }

    public function dataIsValid(): array
    {
        return [
            [true, 1],
            [false, '1'],
            [true, 'foo'],
            [false, null],
            [false, ''],
        ];
    }

    /**
     * @dataProvider dataIsValid
     */
    public function testIsValid(bool $expected, mixed $value): void
    {
        self::assertSame($expected, Pure::isValid($value));
    }

    public function testToValues(): void
    {
        self::assertSame(
            [
                'FOO' => Pure::FOO,
                'BAR' => Pure::BAR,
                'ONE' => Pure::ONE,
                'TWO' => Pure::TWO,
            ],
            Pure::toValues()
        );
    }

    public function testToObjects(): void
    {
        $objects = Pure::toObjects();

        self::assertSame(['FOO', 'BAR', 'ONE', 'TWO'], array_keys($objects));
        self::assertInstanceOf(Pure::class, $objects['FOO']);
        self::assertInstanceOf(Pure::class, $objects['BAR']);
        self::assertInstanceOf(Pure::class, $objects['ONE']);
        self::assertInstanceOf(Pure::class, $objects['TWO']);
        self::assertSame(Pure::FOO, $objects['FOO']->getValue());
        self::assertSame(Pure::BAR, $objects['BAR']->getValue());
        self::assertSame(Pure::ONE, $objects['ONE']->getValue());
        self::assertSame(Pure::TWO, $objects['TWO']->getValue());
    }

    public function testImmutabilityToObjects(): void
    {
        $objects1 = Pure::toObjects();
        $objects2 = Pure::toObjects();

        self::assertNotSame($objects1, $objects2);
    }
}
