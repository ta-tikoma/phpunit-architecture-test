<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Elements;

final class ObjectDescription
{
    public string $path;

    public string $name;

    public array $uses;

    public function __construct(
        string $name,
        string $path,
        array $uses
    ) {
        $this->name = $name;
        $this->path = $path;
        $this->uses = $uses;
    }
}
