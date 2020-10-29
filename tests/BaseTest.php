<?php

declare(strict_types=1);

namespace vjik\enum\tests;

use PHPUnit\Framework\TestCase;
use vjik\enum\tests\enums\Pure;
use vjik\enum\tests\enums\WithData;
use vjik\enum\tests\enums\WithName;

final class BaseTest extends TestCase
{
    protected Pure $pure;
    protected WithName $withName;
    protected WithData $withData;

    protected function setUp(): void
    {
        $this->pure = new Pure(Pure::FOO);
        $this->withName = new WithName(WithName::FOO);
        $this->withData = new WithData(WithData::ONE);
    }

    public function testGetId(): void
    {
        $this->assertEquals(Pure::FOO, $this->pure->id);
        $this->assertEquals((string)Pure::FOO, $this->pure);

        $this->assertEquals(WithName::FOO, $this->withName->id);
        $this->assertEquals((string)WithName::FOO, $this->withName);

        $this->assertEquals(WithData::ONE, $this->withData->id);
        $this->assertEquals((string)WithData::ONE, $this->withData);
    }

    public function testGetName(): void
    {
        $this->assertEquals(Pure::FOO, $this->pure->name);
        $this->assertEquals('Foo Name', $this->withName->name);
        $this->assertEquals('One', $this->withData->name);
    }

    public function testCreate(): void
    {
        $this->assertSame(1, (new Pure(Pure::ONE))->id);
        $this->assertSame(1, Pure::get(Pure::ONE)->id);
        $this->assertSame(1, Pure::ONE()->id);
    }
}
