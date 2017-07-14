<?php

namespace vjik\enum\tests;

use vjik\enum\tests\enums\Pure;
use vjik\enum\tests\enums\WithData;
use vjik\enum\tests\enums\WithName;

class EnumTest extends \PHPUnit_Framework_TestCase
{
    protected $pure;
    protected $withName;
    protected $withData;

    protected function setUp()
    {
        $this->pure = new Pure(Pure::FOO);
        $this->withName = new WithName(WithName::FOO);
        $this->withData = new WithData(WithData::ONE);
    }

    public function testGetValue()
    {
        $this->assertEquals(Pure::FOO, $this->pure->value);
        $this->assertEquals((string)Pure::FOO, $this->pure);

        $this->assertEquals(WithName::FOO, $this->withName->value);
        $this->assertEquals((string)WithName::FOO, $this->withName);

        $this->assertEquals(WithData::ONE, $this->withData->value);
        $this->assertEquals((string)WithData::ONE, $this->withData);
    }

    public function testGetName()
    {
        $this->assertEquals(Pure::FOO, $this->pure->name);
        $this->assertEquals('Foo Name', $this->withName->name);
        $this->assertEquals('One', $this->withData->name);
    }
}
