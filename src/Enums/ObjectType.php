<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static static _CLASS()
 * @method static static _ENUM()
 * @method static static _TRAIT()
 * @method static static _INTERFACE()
 */
final class ObjectType extends Enum
{
    private const _CLASS     = 'class';
    private const _ENUM      = 'enum';
    private const _TRAIT     = 'trait';
    private const _INTERFACE = 'interface';
}
