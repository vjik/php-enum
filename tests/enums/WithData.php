<?php

namespace vjik\enum\tests\enums;

use vjik\enum\Enum;

/**
 * @property int $baseNumber
 */
class WithData extends Enum
{
    const ONE = 1;
    const TWO = 2;
    const THREE = 3;
    const ONE2 = 10;

    protected $number;

    protected function getBaseNumber()
    {
        return $this->number - 100;
    }

    public static function items()
    {
        return [
            self::ONE => [
                'name' => 'One',
                'number' => 101
            ],
            self::TWO => [
                'name' => 'Two',
                'number' => 102
            ],
            self::THREE => [
                'name' => 'Three',
                'number' => 103
            ],
            self::ONE2 => [
                'name' => 'One2',
                'number' => 101
            ],
        ];
    }
}
