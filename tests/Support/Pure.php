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
    public const FOO = 'foo';
    public const BAR = 'bar';
    public const ONE = 1;
    public const TWO = 2;

    public function getName(): mixed
    {
        return $this->getPropertyValue('name');
    }
}
