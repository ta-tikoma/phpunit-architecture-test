<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Storage;

use PHPUnit\Architecture\Elements\ObjectDescription;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class ObjectsStorage
{
    /**
     * @var ObjectDescription[]
     */
    private static $objectMap;

    private static function allFiles()
    {
        /** @var SplFileInfo[] $paths */
        $paths = Finder::create()
            ->files()
            ->followLinks()
            ->name('/\.php$/')
            ->in(Filesystem::getBaseDir());

        foreach ($paths as $path) {
            if ($path->isFile()) {
                yield $path->getRealPath();
            }
        }
    }

    private static function init(): void
    {
        if (self::$objectMap !== null) {
            return;
        }

        self::$objectMap = [];

        foreach (self::allFiles() as $path) {
            $description = ObjectDescription::make($path);
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
