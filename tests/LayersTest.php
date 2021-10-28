<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Architecture\Builders\LayersBuilder;

final class LayersTest extends TestCase
{
    public function test_make_layers_and_assert_depends()
    {
        $layers = LayersBuilder::fromNamespaceRegex('/^(?\'layer\'.*\\\\Architecture\\\\Builders\\\\[^\\\\]+)/m');

        $this->assertDoesNotDependOn($layers, $layers);
    }
}
