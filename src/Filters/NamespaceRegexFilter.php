<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Filters;

use PHPUnit\Architecture\Contracts\FilterContract;
use PHPUnit\Architecture\Elements\ObjectDescription;

/**
 * Filter by namespace regex
 */
final class NamespaceRegexFilter implements FilterContract
{
    public string $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    public function check(ObjectDescription $objectDescription): bool
    {
        return preg_match(
            $this->regex,
            $objectDescription->name,
        ) === 1;
    }
}
