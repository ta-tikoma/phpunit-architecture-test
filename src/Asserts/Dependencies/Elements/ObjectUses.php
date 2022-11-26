<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Dependencies\Elements;

use ArrayIterator;
use IteratorAggregate;

final class ObjectUses implements IteratorAggregate
{
    /**
     * Names of uses objects
     *
     * @var string[] like 'PHPUnit\Architecture\Enums\ObjectType'
     */
    protected array $uses;

    /**
     * @param string[] $uses
     */
    public function __construct(array $uses)
    {
        $this->uses = $uses;
    }

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->uses);
    }

    public function getByName(string $name): ?string
    {
        $length = strlen($name);
        foreach ($this as $use) {
            if (substr($use, -$length, $length) === $name) {
                return $use;
            }
        }

        return null;
    }
}
