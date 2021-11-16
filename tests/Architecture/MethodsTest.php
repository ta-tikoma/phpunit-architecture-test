<?php

declare(strict_types=1);

namespace tests\Architecture;

use tests\TestCase;

final class MethodsTest extends TestCase
{
    public function test_layer_method_incoming_arguments_not_from()
    {
        $tests = $this->layer()->filterByNameStart('tests');
        $filters = $this->layer()->filterByNameStart('PHPUnit\\Architecture\\Filters');

        $this->assertIncomingsNotFrom($filters, $tests);
    }

    // public function test_layer_method_incoming_arguments_from()
    // {
    //     $elements = $this->layer()->filterByNameStart('PHPUnit\\Architecture\\Elements');
    //     $filters = $this->layer()->filterByNameStart('PHPUnit\\Architecture\\Filters');
    //
    //     $this->assertIncomingsFrom($filters, $elements);
    // }

    public function test_layer_method_size()
    {
        $filters = $this->layer()->filterByNameStart('PHPUnit\\Architecture\\Filters');

        $this->assertMethodSizeLessThan($filters, 20);
    }
}
