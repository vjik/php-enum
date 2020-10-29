<?php

declare(strict_types=1);

namespace vjik\enum\tests;

use PHPUnit\Framework\TestCase;
use UnexpectedValueException;
use vjik\enum\tests\enums\WithData;

final class WithDataTest extends TestCase
{
    protected WithData $enum;

    protected function setUp(): void
    {
        $this->enum = new WithData(WithData::ONE);
    }

    public function dataCreateWithInvalidId(): array
    {
        return [
            [0],
            [101],
            ['1'],
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
        new WithData($id);
    }

    public function dataIsValid(): array
    {
        return [
            [0, false],
            [1, true],
            ['1', false],
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
        $this->assertSame(WithData::isValid($id), $isValid);
    }

    public function testToArray(): void
    {
        $this->assertSame([
            WithData::ONE => [
                'name' => 'One',
                'number' => 101,
                'id' => WithData::ONE,
            ],
            WithData::TWO => [
                'name' => 'Two',
                'number' => 102,
                'id' => WithData::TWO,
            ],
            WithData::THREE => [
                'name' => 'Three',
                'number' => 103,
                'id' => WithData::THREE,
            ],
            WithData::ONE2 => [
                'name' => 'One2',
                'number' => 101,
                'id' => WithData::ONE2,
            ],
        ], WithData::toArray());
    }

    public function testToList(): void
    {
        $this->assertSame([
            WithData::ONE => 'One',
            WithData::TWO => 'Two',
            WithData::THREE => 'Three',
            WithData::ONE2 => 'One2',
        ], WithData::toList());
    }

    public function testToIds(): void
    {
        $this->assertSame([
            WithData::ONE,
            WithData::TWO,
            WithData::THREE,
            WithData::ONE2,
        ], WithData::toIds());
    }

    public function testToObjects(): void
    {
        $this->assertEquals([
            WithData::ONE => new WithData(WithData::ONE),
            WithData::TWO => new WithData(WithData::TWO),
            WithData::THREE => new WithData(WithData::THREE),
            WithData::ONE2 => new WithData(WithData::ONE2),
        ], WithData::toObjects());
    }

    public function dataFilter(): array
    {
        return [
            [['number' => 101], [1, 10]],
            [[['=', 'number', 101]], [1, 10]],
            [[['!=', 'number', 101]], [2, 3]],
            [[['>', 'number', 101]], [2, 3]],
            [[['<', 'number', 103]], [1, 2, 10]],
            [[['<=', 'number', 102]], [1, 2, 10]],
            [[['>=', 'number', 102]], [2, 3]],
            [[['>=', 'number', 101], ['<', 'number', 103]], [1, 2, 10]],
            [['number' => 101, 'id' => 1], [1]],
            [['number' => 13], []],
            [[['in', 'number', [101, 102]]], [1, 2, 10]],
            [[['in', 'id', [2, 3]]], [2, 3]],
            [[['in', 'number', [1, 2]]], []],
            [[['numberMore', 101]], [2, 3]],
            [[['numberMore', 102]], [3]],
        ];
    }

    /**
     * @dataProvider dataFilter
     *
     * @param array $filter
     * @param array $ids
     */
    public function testFilter(array $filter, array $ids): void
    {
        $this->assertSame(WithData::toIds($filter), $ids);
    }

    public function testGetter(): void
    {
        $this->assertSame($this->enum->baseNumber, 1);
    }
}
