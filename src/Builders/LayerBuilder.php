<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Builders;

use Closure;
use PHPUnit\Architecture\Elements\Layer;
use PHPUnit\Architecture\Storage\ObjectsStorage;

final class LayerBuilder
{
    /**
     * @param string|string[] $include
     * @param string|string[] $exclude
     */
    public static function fromDirectory($include, $exclude = []): Layer
    {
        $include = array_map(static function (string $line): array {
            return [$line, strlen($line)];
        }, is_array($include) ? $include : [$include]);

        $exclude = array_map(static function (string $line): array {
            return [$line, strlen($line)];
        }, is_array($exclude) ? $exclude : [$exclude]);

        $objectNames = self::byClosure(static function (string $name, string $path) use ($include, $exclude): bool {
            foreach ($exclude as list($line, $length)) {
                /** @var int $length */
                if (substr($path, 0, $length) === $line) {
                    return false;
                }
            }

            foreach ($include as list($line, $length)) {
                /** @var int $length */
                if (substr($path, 0, $length) === $line) {
                    return true;
                }
            }

            return false;
        });

        return new Layer((string) rand(), $objectNames);
    }

    /**
     * @param string|string[] $include
     * @param string|string[] $exclude
     */
    public static function fromNamespace($include, $exclude = []): Layer
    {
        $include = array_map(static function (string $line): array {
            return [$line, strlen($line)];
        }, is_array($include) ? $include : [$include]);

        $exclude = array_map(static function (string $line): array {
            return [$line, strlen($line)];
        }, is_array($exclude) ? $exclude : [$exclude]);

        $objectNames = self::byClosure(static function (string $name, string $path) use ($include, $exclude): bool {
            foreach ($exclude as list($line, $length)) {
                /** @var int $length */
                if (substr($name, 0, $length) === $line) {
                    return false;
                }
            }

            foreach ($include as list($line, $length)) {
                /** @var int $length */
                if (substr($name, 0, $length) === $line) {
                    return true;
                }
            }

            return false;
        });

        return new Layer((string) rand(), $objectNames);
    }

    /**
     * @param Closure $closure function (string $name, string $path): bool
     */
    private static function byClosure(Closure $closure): array
    {
        $objectNames = [];

        foreach (ObjectsStorage::getObjectMap() as $name => $path) {
            if ($closure($name, $path)) {
                $objectNames[] = $name;
            }
        }

        return $objectNames;
    }
}
