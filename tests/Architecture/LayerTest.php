<?php

declare(strict_types=1);

namespace tests\Architecture;

use tests\TestCase;

final class LayerTest extends TestCase
{
    public function test_make_layers_from_namespaces()
    {
        $app = $this->layerFromNamespace('PHPUnit\\Architecture');
        $tests = $this->layerFromNamespace('tests');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_make_layers_from_namespaces_filter()
    {
        $app = $this->layer()
            ->includeNamespace('PHPUnit\\Architecture')
            ->build();

        $tests = $this->layer()
            ->includeNamespace('tests')
            ->build();

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_make_layers_from_directories()
    {
        $app = $this->layerFromDirectory('src');
        $tests = $this->layerFromDirectory('tests');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_make_layers_from_path_filter()
    {
        $app = $this->layer()
            ->includeDirectory('src')
            ->build();

        $tests = $this->layer()
            ->includeDirectory('tests')
            ->build();

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_male_layer_from_namespace_regex_filter()
    {
        $assertTraits = $this->layer()
            ->includeNamespaceRegex('/^PHPUnit\\\\Architecture\\\\Asserts\\\\[^\\\\]+\\\\.+Asserts$/')
            ->build();

        $layer = $this->layer()
            ->includeNamespaceRegex('/^PHPUnit\\\\Architecture\\\\Elements\\\\Layer$/')
            ->build();

        $this->assertDependOn($assertTraits, $layer);
        $this->assertDoesNotDependOn($layer, $assertTraits);
    }
}
