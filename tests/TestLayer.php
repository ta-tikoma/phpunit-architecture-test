<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Architecture\ArchitectureAsserts;
use PHPUnit\Architecture\Builders\LayerBuilder;
use PHPUnit\Framework\TestCase;

final class TestLayer extends TestCase
{
    use ArchitectureAsserts;

    public function test_make_layers_and_assert_depends()
    {
        // $app = LayerBuilder::fromNamespace('PHPUnit\\Architecture');
        $app = LayerBuilder::fromNamespace('PHPUnit');
        // $tests = LayerBuilder::fromNamespace('tests');

        // var_dump($app, $tests);
        var_dump($app);
        die();

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }
}
