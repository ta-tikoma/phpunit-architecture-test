<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Storage;

final class Filesystem
{
    public static function getBaseDir(): string
    {
        return __DIR__ . '/../../';
    }
}
