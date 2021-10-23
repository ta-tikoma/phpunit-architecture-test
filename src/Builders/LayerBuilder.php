<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Builders;

use Closure;
use PHPUnit\Architecture\Elements\Layer;
use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Architecture\Storage\Filesystem;
use PHPUnit\Architecture\Storage\ObjectsStorage;

final class LayerBuilder
{
    /**
     * @param string|string[] $include
     * @param string|string[] $exclude
     */
    public static function fromDirectory($include, $exclude = []): Layer
    {
        $include = is_array($include) ? $include : [$include];
        $exclude = is_array($exclude) ? $exclude : [$exclude];

        $name = md5(implode(',', $include) . implode(',', $exclude));

        $include = array_map(static function (string $line): array {
            $line = realpath(Filesystem::getBaseDir() . $line);
            return [$line, strlen($line)];
        }, $include);

        $exclude = array_map(static function (string $line): array {
            $line = realpath(Filesystem::getBaseDir() . $line);
            return [$line, strlen($line)];
        }, $exclude);

        $objectNames = self::byClosure(static function (ObjectDescription $objectDescription) use ($include, $exclude): bool {
            foreach ($exclude as list($line, $length)) {
                /** @var int $length */
                if (substr($objectDescription->path, 0, $length) === $line) {
                    return false;
                }
            }

            foreach ($include as list($line, $length)) {
                /** @var int $length */
                if (substr($objectDescription->path, 0, $length) === $line) {
                    return true;
                }
            }

            return false;
        });

        return new Layer($name, $objectNames);
    }

    /**
     * @param string|string[] $include
     * @param string|string[] $exclude
     */
    public static function fromNamespace($include, $exclude = []): Layer
    {
        $include = is_array($include) ? $include : [$include];
        $exclude = is_array($exclude) ? $exclude : [$exclude];

        $name = md5(implode(',', $include) . implode(',', $exclude));

        $include = array_map(static function (string $line): array {
            return [$line, strlen($line)];
        }, $include);

        $exclude = array_map(static function (string $line): array {
            return [$line, strlen($line)];
        }, $exclude);

        $objectNames = self::byClosure(static function (ObjectDescription $objectDescription) use ($include, $exclude): bool {
            foreach ($exclude as list($line, $length)) {
                /** @var int $length */
                if (substr($objectDescription->name, 0, $length) === $line) {
                    return false;
                }
            }

            foreach ($include as list($line, $length)) {
                /** @var int $length */
                if (substr($objectDescription->name, 0, $length) === $line) {
                    return true;
                }
            }

            return false;
        });

        return new Layer($name, $objectNames);
    }

    /**
     * @param Closure $closure function (string $name, string $path): bool
     */
    private static function byClosure(Closure $closure): array
    {
        $objectNames = [];

        foreach (ObjectsStorage::getObjectMap() as $objectDescription) {
            if ($closure($objectDescription)) {
                $objectNames[] = $objectDescription->name;
            }
        }

        return $objectNames;
    }
}
