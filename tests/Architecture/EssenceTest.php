<?php

declare(strict_types=1);

namespace tests\Architecture;

use PHPUnit\Architecture\Enums\Visibility;
use tests\TestCase;

final class EssenceTest extends TestCase
{
    public function test_layer_essence()
    {
        $objectParts = $this->layer()
            ->leaveByNameStart('PHPUnit\\Architecture\\Asserts\\')
            ->leaveByNameRegex('/Elements\\\\Object[^\\\\]+$/');

        /** @var Visibility[] $visibilities */
        $visibilities = $objectParts->essence('properties.*.visibility');

        $this->assertNotOne(
            $visibilities,
            fn (Visibility $visibility) => $visibility->equals(Visibility::PRIVATE()),
            fn (string $key, Visibility $visibility) => "Property $key : {$visibility->getValue()} is not private"
        );
    }
}
