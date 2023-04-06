<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static static _CLASS()
 * @method static static _ENUM()
 * @method static static _TRAIT()
 * @method static static _INTERFACE()
 *
 * @extends Enum<string>
 */
final class ObjectType extends Enum
{
    private const _CLASS     = 'class'; // @phpstan-ignore-line
    private const _ENUM      = 'enum'; // @phpstan-ignore-line
    private const _TRAIT     = 'trait'; // @phpstan-ignore-line
    private const _INTERFACE = 'interface'; // @phpstan-ignore-line
}
