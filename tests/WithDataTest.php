<?php

namespace vjik\enum\tests;

use UnexpectedValueException;
use vjik\enum\tests\enums\WithData;

class WithDataTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WithData
     */
    protected $enum;

    protected function setUp()
    {
        $this->enum = new WithData(WithData::ONE);
    }


    /**
     * @dataProvider invalidValueProvider
     * @expectedException UnexpectedValueException
     */
    public function testCreateWithInvalidValue($value)
    {
        new WithData($value);
    }


    /**
     * @return array
     */
    public function invalidValueProvider()
    {
        return [
            [0],
            [101],
            ['1'],
        ];
    }


    /**
     * @dataProvider isValueProvider
     */
    public function testIsValid($value, $isValid)
    {
        $this->assertSame(WithData::isValid($value), $isValid);
    }


    /**
     * @return array
     */
    public function isValueProvider()
    {
        return [
            [0, false],
            [1, true],
            ['1', false],
        ];
    }


    public function testToArray()
    {
        $this->assertSame([
            WithData::ONE => [
                'name' => 'One',
                'number' => 101,
                'value' => WithData::ONE,
            ],
            WithData::TWO => [
                'name' => 'Two',
                'number' => 102,
                'value' => WithData::TWO,
            ],
            WithData::THREE => [
                'name' => 'Three',
                'number' => 103,
                'value' => WithData::THREE,
            ],
            WithData::ONE2 => [
                'name' => 'One2',
                'number' => 101,
                'value' => WithData::ONE2,
            ],
        ], WithData::toArray());
    }


    public function testToList()
    {
        $this->assertSame([
            WithData::ONE => 'One',
            WithData::TWO => 'Two',
            WithData::THREE => 'Three',
            WithData::ONE2 => 'One2',
        ], WithData::toList());
    }


    public function testToValues()
    {
        $this->assertSame([
            WithData::ONE,
            WithData::TWO,
            WithData::THREE,
            WithData::ONE2,
        ], WithData::toValues());
    }


    public function testToObjects()
    {
        $this->assertEquals([
            WithData::ONE => new WithData(WithData::ONE),
            WithData::TWO => new WithData(WithData::TWO),
            WithData::THREE => new WithData(WithData::THREE),
            WithData::ONE2 => new WithData(WithData::ONE2),
        ], WithData::toObjects());
    }


    /**
     * @dataProvider filterProvider
     */
    public function testFilter($filter, $values)
    {
        $this->assertSame(WithData::toValues($filter), $values);
    }


    /**
     * @return array
     */
    public function filterProvider()
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
            [['number' => 101, 'value' => 1], [1]],
            [['number' => 13], []],
            [[['in', 'number', [101, 102]]], [1, 2, 10]],
            [[['in', 'value', [2, 3]]], [2, 3]],
            [[['in', 'number', [1, 2]]], []],
        ];
    }


    public function testGetter()
    {
        $this->assertEquals($this->enum->baseNumber, 1);
    }
}
