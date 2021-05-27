<?php

declare(strict_types=1);

namespace Vjik\Enum\Tests\Support;

use Vjik\Enum\Enum;

/**
 * @method static self ONE()
 * @method static self TWO()
 * @method static self THREE()
 */
final class WithData extends Enum
{
    private const ONE = 1;
    private const TWO = 2;
    private const THREE = 3;

    protected static function data(): array
    {
        return [
            self::ONE => [
                'name' => 'One',
                'number' => 101,
            ],
            self::TWO => [
                'name' => 'Two',
                'number' => 102,
            ],
        ];
    }

    public function getName(): ?string
    {
        return $this->getPropertyValue('name');
    }

    public function getNumber(): ?int
    {
        return $this->getPropertyValue('number');
    }
}
