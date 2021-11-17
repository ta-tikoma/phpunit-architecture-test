<?php

declare(strict_types=1);

namespace tests\Architecture;

use tests\TestCase;

final class EssenceTest extends TestCase
{
    public function test_layer_essence()
    {
        $objectParts = $this->layer()
            ->leaveByNameStart('PHPUnit\\Architecture\\Asserts\\')
            ->leaveByNameRegex('/Elements\\\\Object[^\\\\]+$/');

        var_dump($objectParts->essence('properties.visibility'));
    }
}
