<?php

declare(strict_types=1);

namespace vjik\enum\tests;

use PHPUnit\Framework\TestCase;
use UnexpectedValueException;
use vjik\enum\tests\enums\WithName;

final class WithNameTest extends TestCase
{
    protected WithName $enum;

    protected function setUp(): void
    {
        $this->enum = new WithName(WithName::FOO);
    }

    public function dataCreateWithInvalidId(): array
    {
        return [
            [0],
            [1],
            ['Foo'],
            ['Bar Name'],
        ];
    }

    /**
     * @dataProvider dataCreateWithInvalidId
     * @param mixed $id
     */
    public function testCreateWithInvalidId($id): void
    {
        $this->expectException(UnexpectedValueException::class);
        new WithName($id);
    }

    public function dataIsValid(): array
    {
        return [
            [0, false],
            [1, false],
            ['foo', true],
            ['Foo Name', false],
        ];
    }

    /**
     * @dataProvider dataIsValid
     *
     * @param mixed $id
     * @param bool $isValid
     */
    public function testIsValid($id, bool $isValid): void
    {
        $this->assertSame(WithName::isValid($id), $isValid);
    }

    public function testToArray(): void
    {
        $this->assertSame([
            WithName::FOO => [
                'name' => 'Foo Name',
                'id' => WithName::FOO,
            ],
            WithName::BAR => [
                'name' => 'Bar Name',
                'id' => WithName::BAR,
            ],
        ], WithName::toArray());
    }

    public function testToList(): void
    {
        $this->assertSame([
            WithName::FOO => 'Foo Name',
            WithName::BAR => 'Bar Name',
        ], WithName::toList());
    }

    public function testToIds(): void
    {
        $this->assertSame([
            WithName::FOO,
            WithName::BAR,
        ], WithName::toIds());
    }

    public function testToObjects(): void
    {
        $this->assertEquals([
            WithName::FOO => new WithName(WithName::FOO),
            WithName::BAR => new WithName(WithName::BAR),
        ], WithName::toObjects());
    }
}
