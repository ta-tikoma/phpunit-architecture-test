<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Builders;

use PHPUnit\Architecture\Elements\Layer\Layer;
use PHPUnit\Architecture\Storage\ObjectsStorage;

trait BuildFromTest
{
    private static ?Layer $layer = null;

    public function layer(): Layer
    {
        if (self::$layer === null) {
            self::$layer = new Layer(ObjectsStorage::getObjectMap());
        }

        return self::$layer;
    }
}
