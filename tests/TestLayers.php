<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Architecture\ArchitectureAsserts;
use PHPUnit\Architecture\Builders\LayersBuilder;
use PHPUnit\Framework\TestCase;

final class TestLayers extends TestCase
{
    use ArchitectureAsserts;

    public function test_make_layers_and_assert_depends()
    {
        $layers = LayersBuilder::fromNamespaceRegex('/^(?\'name\'.*\\\\Architecture\\\\[^\\\\]+)/m');
        var_dump($layers);
    }
}
