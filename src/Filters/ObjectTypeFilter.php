<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Filters;

use PHPUnit\Architecture\Contracts\FilterContract;
use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Architecture\Enums\ObjectType;

/**
 * Filter by object type
 */
final class ObjectTypeFilter implements FilterContract
{
    public ObjectType $type;

    public function __construct(ObjectType $type)
    {
        $this->type = $type;
    }

    public function check(ObjectDescription $objectDescription): bool
    {
        return $objectDescription->type->equals($this->type);
    }
}
