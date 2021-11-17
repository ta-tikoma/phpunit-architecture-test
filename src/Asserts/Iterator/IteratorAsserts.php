<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Iterator;

use Closure;
use IteratorAggregate;

trait IteratorAsserts
{
    abstract public static function assertTrue($condition, string $message = ''): void;

    /**
     * @property array|IteratorAggregate $list
     * @property Closure $check static function(item_of_list $item): bool
     * @property Closure $message static function(string $key, item_of_list $item): string
     */
    public function assertEach($list, Closure $check, Closure $message): void
    {
        foreach ($list as $key => $item) {
            if (!$check($item)) {
                self::assertTrue(false, $message($key, $item));
            }
        }

        self::assertTrue(true);
    }

    /**
     * @property array|IteratorAggregate $list
     * @property Closure $check static function(item_of_list $item): bool
     * @property Closure $message static function(string $key, item_of_list $item): string
     */
    public function assertNotOne($list, Closure $check, Closure $message): void
    {
        foreach ($list as $key => $item) {
            if ($check($item)) {
                self::assertTrue(false, $message($key, $item));
            }
        }

        self::assertTrue(true);
    }

    /**
     * @property array|IteratorAggregate $list
     * @property Closure $check static function(item_of_list $item): bool
     */
    public function assertAny($list, Closure $check, string $message): void
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
