<?php

namespace vjik\enum\tests;

use UnexpectedValueException;
use vjik\enum\tests\enums\WithName;

class WithNameTest extends \PHPUnit_Framework_TestCase
{
    protected $enum;

    protected function setUp()
    {
        $this->enum = new WithName(WithName::FOO);
    }


    /**
     * @dataProvider invalidValueProvider
     * @expectedException UnexpectedValueException
     */
    public function testCreateWithInvalidValue($value)
    {
        new WithName($value);
    }


    /**
     * @return array
     */
    public function invalidValueProvider()
    {
        return [
            [0],
            [1],
            ['Foo'],
            ['Bar Name'],
        ];
    }


    /**
     * @dataProvider isValueProvider
     */
    public function testIsValid($value, $isValid)
    {
        $this->assertSame(WithName::isValid($value), $isValid);
    }


    /**
     * @return array
     */
    public function isValueProvider()
    {
        return [
            [0, false],
            [1, false],
            ['foo', true],
            ['Foo Name', false],
        ];
    }


    public function testToArray()
    {
        $this->assertSame([
            WithName::FOO => [
                'name' => 'Foo Name',
                'value' => WithName::FOO,
            ],
            WithName::BAR => [
                'name' => 'Bar Name',
                'value' => WithName::BAR,
            ],
        ], WithName::toArray());
    }


    public function testToList()
    {
        $this->assertSame([
            WithName::FOO => 'Foo Name',
            WithName::BAR => 'Bar Name',
        ], WithName::toList());
    }


    public function testToValues()
    {
        $this->assertSame([
            WithName::FOO,
            WithName::BAR,
        ], WithName::toValues());
    }
}
