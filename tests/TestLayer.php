<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Architecture\ArchitectureAsserts;
use PHPUnit\Architecture\Builders\LayerBuilder;
use PHPUnit\Framework\TestCase;

final class TestLayer extends TestCase
{
    use ArchitectureAsserts;

    public function test_make_layers_from_namespaces()
    {
        $app = LayerBuilder::fromNamespace('PHPUnit\\Architecture');
        $tests = LayerBuilder::fromNamespace('tests');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_male_layers_from_directories()
    {
        $app = LayerBuilder::fromDirectory('src');
        $tests = LayerBuilder::fromDirectory('tests');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }
}
