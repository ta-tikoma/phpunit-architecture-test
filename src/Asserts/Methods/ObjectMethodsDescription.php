<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Methods;

use PhpParser\Node;
use PHPUnit\Architecture\Asserts\Dependencies\ObjectDependenciesDescription;
use PHPUnit\Architecture\Elements\MethodDescription;

/**
 * Describe object methods
 */
class ObjectMethodsDescription extends ObjectDependenciesDescription
{
    /**
     * Names of uses objects
     *
     * @var MethodDescription[]
     */
    public array $methods;

    public static function make(string $path): ?self
    {
        $description = parent::make($path);

        if ($description === null) {
            return null;
        }

        /** @var Node\Stmt\ClassMethod[] $methods */
        $methods = self::$nodeFinder->findInstanceOf($description->stmts, Node\Stmt\ClassMethod::class);
        $description->methods = array_map(static function (Node\Stmt\ClassMethod $classMethod): MethodDescription {
            return MethodDescription::make($classMethod);
        }, $methods);

        return $description;
    }
}
