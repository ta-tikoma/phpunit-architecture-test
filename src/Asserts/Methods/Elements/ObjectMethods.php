<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Methods\Elements;

use ArrayIterator;
use IteratorAggregate;

final class ObjectMethods implements IteratorAggregate
{
    /**
     * Object methods
     *
     * @var MethodDescription[]
     */
    protected array $methods;

    /**
     * @param MethodsDescription[] $methods
     */
    public function __construct(array $methods)
    {
        $this->methods = $methods;
    }

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->methods);
    }
}
