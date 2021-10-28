<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Filters;

use Closure;
use PHPUnit\Architecture\Contracts\FilterContract;
use PHPUnit\Architecture\Elements\ObjectDescription;

final class ClosureFilter implements FilterContract
{
    public Closure $closure;

    /**
     * @param Closure $closure Contract: static function (ObjectDescription $objectDescription): bool
     */
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function check(ObjectDescription $objectDescription): bool
    {
        $closure = $this->closure;
        return $closure($objectDescription);
    }
}
