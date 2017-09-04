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
     * @dataProvider invalidIdProvider
     * @expectedException UnexpectedValueException
     */
    public function testCreateWithInvalidId($id)
    {
        new Pure($id);
    }


    /**
     * @return array
     */
    public function invalidIdProvider()
    {
        return [
            [0],
            [101],
            ['1'],
            ['01'],
        ];
    }


    /**
     * @dataProvider isIdProvider
     */
    public function testIsValid($id, $isValid)
    {
        $this->assertSame(Pure::isValid($id), $isValid);
    }


    /**
     * @return array
     */
    public function isIdProvider()
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
                'id' => Pure::FOO,
            ],
            Pure::BAR => [
                'name' => Pure::BAR,
                'id' => Pure::BAR,
            ],
            Pure::ONE => [
                'name' => Pure::ONE,
                'id' => Pure::ONE,
            ],
            Pure::TWO => [
                'name' => Pure::TWO,
                'id' => Pure::TWO,
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


    public function testToIds()
    {
        $this->assertSame([
            Pure::FOO,
            Pure::BAR,
            Pure::ONE,
            Pure::TWO,
        ], Pure::toIds());
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
