<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Services;

use PHPUnit\Architecture\Elements\ObjectDescription;
use Symfony\Component\Finder\Finder;

/**
 * For redefined to make extension
 */
final class ServiceContainer
{
    public static ?Finder $finder = null;

    public static string $descriptionClass = ObjectDescription::class;
}
