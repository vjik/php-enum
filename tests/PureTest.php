<?php

declare(strict_types=1);

namespace vjik\enum\tests;

use PHPUnit\Framework\TestCase;
use UnexpectedValueException;
use vjik\enum\tests\enums\Pure;

final class PureTest extends TestCase
{
    protected Pure $enum;

    protected function setUp(): void
    {
        $this->enum = new Pure(Pure::FOO);
    }

    public function dataCreateWithInvalidId(): array
    {
        return [
            [0],
            [101],
            ['1'],
            ['01'],
        ];
    }

    /**
     * @dataProvider dataCreateWithInvalidId
     *
     * @param mixed $id
     */
    public function testCreateWithInvalidId($id): void
    {
        $this->expectException(UnexpectedValueException::class);
        new Pure($id);
    }

    public function dataIsValid(): array
    {
        return [
            [0, false],
            [1, true],
            ['1', false],
            ['01', false],
            [2, true],
            ['foo', true],
            ['bar', true],
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
        $this->assertSame(Pure::isValid($id), $isValid);
    }

    public function testToArray(): void
    {
        $this->assertSame([
            Pure::FOO => [
                'id' => Pure::FOO,
                'name' => Pure::FOO,
            ],
            Pure::BAR => [
                'id' => Pure::BAR,
                'name' => Pure::BAR,
            ],
            Pure::ONE => [
                'id' => Pure::ONE,
                'name' => Pure::ONE,
            ],
            Pure::TWO => [
                'id' => Pure::TWO,
                'name' => Pure::TWO,
            ],
        ], Pure::toArray());
    }


    public function testToList(): void
    {
        $this->assertSame([
            Pure::FOO => Pure::FOO,
            Pure::BAR => Pure::BAR,
            Pure::ONE => Pure::ONE,
            Pure::TWO => Pure::TWO,
        ], Pure::toList());
    }


    public function testToIds(): void
    {
        $this->assertSame([
            Pure::FOO,
            Pure::BAR,
            Pure::ONE,
            Pure::TWO,
        ], Pure::toIds());
    }


    public function testToObjects(): void
    {
        $this->assertEquals([
            Pure::FOO => new Pure(Pure::FOO),
            Pure::BAR => new Pure(Pure::BAR),
            Pure::ONE => new Pure(Pure::ONE),
            Pure::TWO => new Pure(Pure::TWO),
        ], Pure::toObjects());
    }
}
