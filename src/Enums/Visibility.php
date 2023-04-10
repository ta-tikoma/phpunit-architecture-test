<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Enums;

use MyCLabs\Enum\Enum;

/**
 * @todo change to Enum
 *
 * @method static static PRIVATE()
 * @method static static PUBLIC()
 * @method static static PROTECTED()
 *
 * @extends Enum<string>
 */
final class Visibility extends Enum
{
    private const PRIVATE   = 'private'; // @phpstan-ignore-line
    private const PUBLIC    = 'public'; // @phpstan-ignore-line
    private const PROTECTED = 'protected'; // @phpstan-ignore-line
}
