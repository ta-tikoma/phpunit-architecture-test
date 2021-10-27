<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Contracts;

use PHPUnit\Architecture\Elements\ObjectDescription;

interface FilterContract
{
    /**
     * @return bool|string
     */
    public function check(ObjectDescription $objectDescription);

    public function __toString(): string;
}
