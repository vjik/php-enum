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
     * @dataProvider invalidIdProvider
     * @expectedException UnexpectedValueException
     */
    public function testCreateWithInvalidId($id)
    {
        new WithName($id);
    }


    /**
     * @return array
     */
    public function invalidIdProvider()
    {
        return [
            [0],
            [1],
            ['Foo'],
            ['Bar Name'],
        ];
    }


    /**
     * @dataProvider isIdProvider
     */
    public function testIsValid($id, $isValid)
    {
        $this->assertSame(WithName::isValid($id), $isValid);
    }


    /**
     * @return array
     */
    public function isIdProvider()
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
                'id' => WithName::FOO,
            ],
            WithName::BAR => [
                'name' => 'Bar Name',
                'id' => WithName::BAR,
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


    public function testToIds()
    {
        $this->assertSame([
            WithName::FOO,
            WithName::BAR,
        ], WithName::toIds());
    }


    public function testToObjects()
    {
        $this->assertEquals([
            WithName::FOO => new WithName(WithName::FOO),
            WithName::BAR => new WithName(WithName::BAR),
        ], WithName::toObjects());
    }
}
