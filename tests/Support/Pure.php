<?php

declare(strict_types=1);

namespace Vjik\Enum\Tests\Support;

use Vjik\Enum\Enum;

/**
 * @method static self FOO()
 * @method static self BAR()
 * @method static self ONE()
 * @method static self TWO()
 */
final class Pure extends Enum
{
    private const FOO = 'foo';
    private const BAR = 'bar';
    private const ONE = 1;
    private const TWO = 2;

    public function getName(): mixed
    {
        return $this->getPropertyValue('name');
    }
}
