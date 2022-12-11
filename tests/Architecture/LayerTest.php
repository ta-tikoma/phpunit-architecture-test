<?php

declare(strict_types=1);

namespace tests\Architecture;

use PHPUnit\Architecture\Enums\ObjectType;
use PHPUnit\Architecture\Enums\Visibility;
use tests\TestCase;

final class LayerTest extends TestCase
{
    public function test_make_layers_from_directories()
    {
        $app = $this->layer()->leaveByPathStart('src');
        $tests = $this->layer()->leaveByPathStart('tests');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_layer_dependens_in_own_namespace()
    {
        $objectTypeEnum = $this->layer()->leaveByNameStart(ObjectType::class);
        $visibilityEnum  = $this->layer()->leaveByNameStart(Visibility::class);

        $this->assertDoesNotDependOn($objectTypeEnum, $visibilityEnum);

        $class = $this->layer()->leaveByNameRegex('/^PHPUnit\\\\Architecture\\\\Elements\\\\Layer\\\\Layer$/');
        $trait  = $this->layer()->leaveByNameStart('PHPUnit\\Architecture\\Elements\\Layer\\LayerLeave');

        $this->assertDependOn($class, $trait);
    }

    public function test_make_layers_from_namespaces()
    {
        $tests = $this->layer()->leaveByNameStart('tests');
        $app = $this->layer()->leaveByNameStart('PHPUnit\\Architecture');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

    public function test_male_layer_from_namespace_regex_filter()
    {
        $assertTraits = $this->layer()
            ->leaveByNameRegex('/^PHPUnit\\\\Architecture\\\\Asserts\\\\[^\\\\]+\\\\.+Asserts$/');

        $layer = $this->layer()
            ->leaveByNameRegex('/^PHPUnit\\\\Architecture\\\\Elements\\\\Layer\\\\Layer$/');

        $this->assertDependOn($assertTraits, $layer);
        $this->assertDoesNotDependOn($layer, $assertTraits);
    }

    public function test_layer_create_by_type()
    {
        $traits = $this->layer()
            ->leaveByNameRegex('/^PHPUnit\\\\Architecture\\\\Asserts\\\\[^\\\\]+\\\\.+Asserts$/');

        $traitsCheck = $this->layer()
            ->leaveByNameStart('PHPUnit\\Architecture\\Asserts')
            ->leaveByType(ObjectType::_TRAIT());

        $this->assertTrue($traits->equals($traitsCheck));
    }
}
