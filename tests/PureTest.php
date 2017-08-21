<?php

namespace vjik\enum\tests;

use UnexpectedValueException;
use vjik\enum\tests\enums\Pure;

class PureTest extends \PHPUnit_Framework_TestCase
{
    protected $enum;

    protected function setUp()
    {
        $this->enum = new Pure(Pure::FOO);
    }


    /**
     * @dataProvider invalidValueProvider
     * @expectedException UnexpectedValueException
     */
    public function testCreateWithInvalidValue($value)
    {
        new Pure($value);
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
            ['01'],
        ];
    }


    /**
     * @dataProvider isValueProvider
     */
    public function testIsValid($value, $isValid)
    {
        $this->assertSame(Pure::isValid($value), $isValid);
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
            ['01', false],
            [2, true],
            ['foo', true],
            ['bar', true],
        ];
    }


    public function testToArray()
    {
        $this->assertSame([
            Pure::FOO => [
                'name' => Pure::FOO,
                'value' => Pure::FOO,
            ],
            Pure::BAR => [
                'name' => Pure::BAR,
                'value' => Pure::BAR,
            ],
            Pure::ONE => [
                'name' => Pure::ONE,
                'value' => Pure::ONE,
            ],
            Pure::TWO => [
                'name' => Pure::TWO,
                'value' => Pure::TWO,
            ],
        ], Pure::toArray());
    }


    public function testToList()
    {
        $this->assertSame([
            Pure::FOO => Pure::FOO,
            Pure::BAR => Pure::BAR,
            Pure::ONE => Pure::ONE,
            Pure::TWO => Pure::TWO,
        ], Pure::toList());
    }


    public function testToValues()
    {
        $this->assertSame([
            Pure::FOO,
            Pure::BAR,
            Pure::ONE,
            Pure::TWO,
        ], Pure::toValues());
    }


    public function testToObjects()
    {
        $this->assertEquals([
            Pure::FOO => new Pure(Pure::FOO),
            Pure::BAR => new Pure(Pure::BAR),
            Pure::ONE => new Pure(Pure::ONE),
            Pure::TWO => new Pure(Pure::TWO),
        ], Pure::toObjects());
    }
}
