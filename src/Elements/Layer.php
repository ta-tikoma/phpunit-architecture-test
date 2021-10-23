<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Elements;

final class Layer
{
    public string $name;

    /**
     * @var string[]
     */
    public array $objectsName = [];

    public function __construct(
        string $name,
        array $objectsName
    ) {
        $this->name = $name;
        $this->objectsName = $objectsName;
    }
}
