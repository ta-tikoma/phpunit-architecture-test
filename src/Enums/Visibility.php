<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static static PRIVATE()
 * @method static static PUBLIC()
 * @method static static PROTECTED()
 */
final class Visibility extends Enum
{
    private const PRIVATE   = 'private';
    private const PUBLIC    = 'public';
    private const PROTECTED = 'protected';
}
