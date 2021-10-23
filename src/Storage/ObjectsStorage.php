<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Storage;

use Composer\Autoload\ClassLoader;
use Symfony\Component\Finder\Finder;

final class ObjectsStorage
{
    private static $objectMap;

    private static function getDir(): string
    {
        return __DIR__ . '/../';
    }

    private static function init(): void
    {
        if (self::$objectMap !== null) {
            return;
        }

        $paths = Finder::create()
            ->files()
            ->followLinks()
            ->name('/\.php$/')
            ->in(self::getDir());

        foreach ($paths as $path) {
        }

        // $loader = new ClassLoader(self::getDir());
        //
        // $map = require self::getDir() . '/composer/autoload_namespaces.php';
        // foreach ($map as $namespace => $path) {
        //     $loader->set($namespace, $path);
        // }
        //
        // $map = require self::getDir() . '/composer/autoload_psr4.php';
        // foreach ($map as $namespace => $path) {
        //     $loader->setPsr4($namespace, $path);
        // }
        //
        // $classMap = require self::getDir() . '/composer/autoload_classmap.php';
        // if ($classMap) {
        //     $loader->addClassMap($classMap);
        // }

        self::$objectMap = $loader->getClassMap();
    }

    public static function getObjectMap(): array
    {
        self::init();

        return self::$objectMap;
    }
}
