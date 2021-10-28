<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Contracts;

use PHPUnit\Architecture\Elements\ObjectDescription;

interface FilterContract
{
    public function check(ObjectDescription $objectDescription): bool;
}
