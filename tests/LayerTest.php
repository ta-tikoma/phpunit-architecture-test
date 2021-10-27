<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Architecture\Builders\LayerBuilder;

final class LayerTest extends TestCase
{
    public function test_make_layers_from_namespaces()
    {
        $app = LayerBuilder::fromNamespace('PHPUnit\\Architecture');
        $tests = LayerBuilder::fromNamespace('tests');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_make_layers_from_directories()
    {
        $app = LayerBuilder::fromDirectory('src');
        $tests = LayerBuilder::fromDirectory('tests');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_make_layers_from_filter()
    {
        $app = (new LayerBuilder)->includePath('src')->build();
        $tests = (new LayerBuilder)->includePath('tests')->build();

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }
}
