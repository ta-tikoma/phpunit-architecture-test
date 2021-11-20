<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Dependencies;

use PhpParser\Node;
use PHPUnit\Architecture\Asserts\Dependencies\Elements\ObjectUses;
use PHPUnit\Architecture\Elements\ObjectDescriptionBase;
use PHPUnit\Architecture\Services\ServiceContainer;

/**
 * Describe object dependencies
 */
class ObjectDependenciesDescription extends ObjectDescriptionBase
{
    /**
     * Names of uses objects
     */
    public ObjectUses $uses;

    public static function make(string $path): ?self
    {
        $description = parent::make($path);

        if ($description === null) {
            return null;
        }

        /** @var Node\Stmt\UseUse[] $useUses */
        $useUses = ServiceContainer::$nodeFinder->findInstanceOf($description->stmts, Node\Stmt\UseUse::class);

        $description->uses = new ObjectUses(
            array_map(
                static function (Node\Stmt\UseUse $useUse): string {
                    return $useUse->name->toCodeString();
                },
                $useUses
            )
        );

        return $description;
    }
}
