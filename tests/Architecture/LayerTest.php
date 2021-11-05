<?php

declare(strict_types=1);

namespace tests\Architecture;

use PHPUnit\Architecture\Enums\ObjectType;
use tests\TestCase;

final class LayerTest extends TestCase
{
    public function test_make_layers_from_namespaces()
    {
        $app = $this->layerFromNameStart('PHPUnit\\Architecture');
        $tests = $this->layer()
            ->includeNameStart('tests')
            ->build();

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_make_layers_from_directories()
    {
        $app = $this->layerFromPath('src');
        $tests = $this->layer()
            ->includePath('tests')
            ->build();

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_male_layer_from_namespace_regex_filter()
    {
        $assertTraits = $this->layer()
            ->includeNameRegex('/^PHPUnit\\\\Architecture\\\\Asserts\\\\[^\\\\]+\\\\.+Asserts$/')
            ->build();

        $layer = $this->layer()
            ->includeNameRegex('/^PHPUnit\\\\Architecture\\\\Elements\\\\Layer$/')
            ->build();

        $this->assertDependOn($assertTraits, $layer);
        $this->assertDoesNotDependOn($layer, $assertTraits);
    }

    public function test_layer_create_by_type()
    {
        $traits = $this->layer()
            ->includeNameRegex('/^PHPUnit\\\\Architecture\\\\Asserts\\\\[^\\\\]+\\\\.+Asserts$/')
            ->build();

        $traitsCheck = $this->layer()
            ->includeNameStart('PHPUnit\\Architecture\\Asserts')
            ->excludeObjectType(ObjectType::_CLASS())
            ->excludeObjectType(ObjectType::_INTERFACE())
            ->build();

        $this->assertTrue($traits->equals($traitsCheck));
    }
}
