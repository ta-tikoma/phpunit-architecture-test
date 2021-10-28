<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Storage;

use PHPUnit\Architecture\Asserts\Dependencies\ObjectDependenciesDescription;
use PHPUnit\Architecture\Elements\ObjectDescription;

final class ObjectsStorage
{
    public static string $descriptionClass = ObjectDependenciesDescription::class;

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
            $description = self::$descriptionClass::make($path);
            if ($description === null) {
                continue;
            }

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
