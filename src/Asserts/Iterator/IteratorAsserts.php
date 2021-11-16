<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Iterator;

use Closure;
use IteratorAggregate;

trait IteratorAsserts
{
    abstract public static function assertTrue($condition, string $message = ''): void;

    /**
     * @property Closure $check static function(item_of_list $item): bool
     * @property Closure $message static function(item_of_list $item): string
     */
    public function assertEach(IteratorAggregate $list, Closure $check, Closure $message): void
    {
        foreach ($list as $item) {
            if (!$check($item)) {
                self::assertTrue(false, $message($item));
            }
        }
    }

    /**
     * @property Closure $check static function(item_of_list $item): bool
     * @property Closure $message static function(item_of_list $item): string
     */
    public function assertNotOne(IteratorAggregate $list, Closure $check, Closure $message): void
    {
        foreach ($list as $item) {
            if ($check($item)) {
                self::assertTrue(false, $message($item));
            }
        }
    }

    /**
     * @property Closure $check static function(item_of_list $item): bool
     */
    public function assertAny(IteratorAggregate $list, Closure $check, string $message): void
    {
        $isTrue = false;
        foreach ($list as $item) {
            if ($check($item)) {
                $isTrue = true;
            }
        }

        self::assertTrue($isTrue, $message);
    }
}
