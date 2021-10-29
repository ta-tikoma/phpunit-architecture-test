<?php

declare(strict_types=1);

namespace tests;

final class MethodsTest extends TestCase
{
    public function test_layer_method_incoming_arguments_not_from()
    {
        $tests = $this->layerFromNamespace('tests');
        $filters = $this->layerFromNamespace('PHPUnit\\Architecture\\Filters');

        $this->assertIncomingsNotFrom($filters, $tests);
    }

    public function test_layer_method_incoming_arguments_from()
    {
        $elements = $this->layerFromNamespace('PHPUnit\\Architecture\\Elements');
        $filters = $this->layerFromNamespace('PHPUnit\\Architecture\\Filters');

        $this->assertIncomingsFrom($filters, $elements);
    }
}
