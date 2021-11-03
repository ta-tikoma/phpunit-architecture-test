<?php

declare(strict_types=1);

namespace PHPUnit\Architecture;

use PHPUnit\Architecture\Asserts\Dependencies\DependenciesAsserts;
use PHPUnit\Architecture\Asserts\Methods\MethodsAsserts;
use PHPUnit\Architecture\Asserts\Properties\PropertiesAsserts;
use PHPUnit\Architecture\Builders\BuildFromTest;

/**
 * Asserts for testing architecture
 */
trait ArchitectureAsserts
{
    use BuildFromTest;
    use DependenciesAsserts;
    use MethodsAsserts;
    use PropertiesAsserts;
}
