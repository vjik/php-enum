<?php

declare(strict_types=1);

namespace Vjik\Enum\Tests;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use ValueError;
use Vjik\Enum\Tests\Support\Pure;
use Vjik\Enum\Tests\Support\WithData;

final class BaseTest extends TestCase
{
    public function testCreateViaFrom(): void
    {
        $foo = Pure::from('foo');
        self::assertSame('foo', $foo->getValue());
    }

    public function testImmutabilityCreateViaFrom(): void
    {
        self::assertSame(Pure::from(1), Pure::from(1));
    }

    public function testCreateViaFunction(): void
    {
        $foo = Pure::FOO();
        self::assertSame('foo', $foo->getValue());
    }

    public function testImmutabilityCreateViaFunction(): void
    {
        self::assertSame(Pure::FOO(), Pure::FOO());
    }

    public function testCreateWithInvalidValue(): void
    {
        $this->expectException(ValueError::class);
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

    public function testValues(): void
    {
        self::assertSame(
            ['foo', 'bar', 1, 2],
            Pure::values(),
        );
    }

    public function testCases(): void
    {
        $objects = Pure::cases();

        self::assertCount(4, $objects);
        self::assertInstanceOf(Pure::class, $objects[0]);
        self::assertInstanceOf(Pure::class, $objects[1]);
        self::assertInstanceOf(Pure::class, $objects[2]);
        self::assertInstanceOf(Pure::class, $objects[3]);
        self::assertSame('foo', $objects[0]->getValue());
        self::assertSame('bar', $objects[1]->getValue());
        self::assertSame(1, $objects[2]->getValue());
        self::assertSame(2, $objects[3]->getValue());
    }

    public function testImmutabilityCases(): void
    {
        $objects1 = Pure::cases();
        $objects2 = Pure::cases();

        self::assertSame($objects1, $objects2);
    }
}
