<?php

declare(strict_types=1);

namespace vjik\enum\tests\enums;

use vjik\enum\Enum;

final class WithName extends Enum
{
    const FOO = 'foo';
    const BAR = 'bar';

    public static function items(): array
    {
        return [
            self::FOO => 'Foo Name',
            self::BAR => 'Bar Name',
        ];
    }
}
