<?php

declare(strict_types=1);

namespace tests\Architecture;

use PHPUnit\Architecture\Enums\ObjectType;
use tests\TestCase;

final class LayerTest extends TestCase
{
    public function test_make_layers_from_directories()
    {
        $app = $this->layer()->filterByPathStart('src');
        $tests = $this->layer()->filterByPathStart('tests');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_make_layers_from_namespaces()
    {
        $tests = $this->layer()->filterByNameStart('tests');
        $app = $this->layer()->filterByNameStart('PHPUnit\\Architecture');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }


    public function test_male_layer_from_namespace_regex_filter()
    {
        $assertTraits = $this->layer()
            ->filterByNameRegex('/^PHPUnit\\\\Architecture\\\\Asserts\\\\[^\\\\]+\\\\.+Asserts$/');

        $layer = $this->layer()
            ->filterByNameRegex('/^PHPUnit\\\\Architecture\\\\Elements\\\\Layer\\\\Layer$/');

        $this->assertDependOn($assertTraits, $layer);
        $this->assertDoesNotDependOn($layer, $assertTraits);
    }

    public function test_layer_create_by_type()
    {
        $traits = $this->layer()
            ->filterByNameRegex('/^PHPUnit\\\\Architecture\\\\Asserts\\\\[^\\\\]+\\\\.+Asserts$/');

        $traitsCheck = $this->layer()
            ->filterByNameStart('PHPUnit\\Architecture\\Asserts')
            ->filterByType(ObjectType::_TRAIT());

        $this->assertTrue($traits->equals($traitsCheck));
    }
}
