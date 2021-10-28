<?php

declare(strict_types=1);

namespace PHPUnit\Architecture;

use PHPUnit\Architecture\Asserts\Dependencies\DependenciesAsserts;

/**
 * Asserts for testing architecture
 */
trait ArchitectureAsserts
{
    use DependenciesAsserts;
}
