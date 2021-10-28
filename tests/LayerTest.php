<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Architecture\Builders\LayerBuilder;

final class LayerTest extends TestCase
{
    public function test_make_layers_from_namespaces()
    {
        $app = (new LayerBuilder)
            ->includeNamespace('PHPUnit\\Architecture')
            ->build();

        $tests = (new LayerBuilder)
            ->includeNamespace('tests')
            ->build();

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_make_layers_from_directories()
    {
        $app = (new LayerBuilder)
            ->includePath('src')
            ->build();

        $tests = (new LayerBuilder)
            ->includePath('tests')
            ->build();

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }
}
