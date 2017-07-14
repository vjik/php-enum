<?php

namespace vjik\enum\tests\enums;

use vjik\enum\Enum;

class WithName extends Enum
{
    const FOO = 'foo';
    const BAR = 'bar';

    public static function items()
    {
        return [
            self::FOO => 'Foo Name',
            self::BAR => 'Bar Name',
        ];
    }
}
