<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Storage;

use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Architecture\Services\ServiceContainer;

final class ObjectsStorage
{
    /**
     * @var ObjectDescription[]
     */
    private static $objectMap;

    private static function init(): void
    {
        if (self::$objectMap !== null) {
            return;
        }

        self::$objectMap = [];

        foreach (Filesystem::files() as $path) {
            /** @var ObjectDescription $description */
            $description = ServiceContainer::$descriptionClass::make($path);
            if ($description === null) {
                continue;
            }

            // save memory
            $description->stmts = [];

            self::$objectMap[$description->name] = $description;
        }
    }

    /**
     * @return ObjectDescription[]
     */
    public static function getObjectMap(): array
    {
        self::init();

        return self::$objectMap;
    }
}
