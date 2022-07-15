<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Elements;

use Exception;
use PhpParser\Node;
use PHPUnit\Architecture\Enums\ObjectType;
use PHPUnit\Architecture\Services\ServiceContainer;
use ReflectionClass;

class ObjectDescriptionBase
{
    public ObjectType $type;

    public string $path;

    public string $name;

    public array $stmts;

    public ReflectionClass $reflectionClass;

    public static function make(string $path): ?self
    {
        $content = file_get_contents($path);

        try {
            $ast = ServiceContainer::$parser->parse($content);
        } catch (Exception $e) {
            echo "Path: $path Exception: {$e->getMessage()}";
        }

        if ($ast === null) {
            return null;
        }

        $stmts = ServiceContainer::$nodeTraverser->traverse($ast);

        /** @var Node\Stmt\Class_|Node\Stmt\Trait_|Node\Stmt\Interface_ $object */
        $object = ServiceContainer::$nodeFinder->findFirst($stmts, function (Node $node) {
            return $node instanceof Node\Stmt\Class_
                || $node instanceof Node\Stmt\Trait_
                || $node instanceof Node\Stmt\Interface_
                // 
            ;
        });

        if ($object === null) {
            return null;
        }

        if (!property_exists($object, 'namespacedName')) {
            return null;
        }

        $description = new static();

        if ($object instanceof Node\Stmt\Class_) {
            $description->type = ObjectType::_CLASS();
        } elseif ($object instanceof Node\Stmt\Trait_) {
            $description->type = ObjectType::_TRAIT();
        } elseif ($object instanceof Node\Stmt\Interface_) {
            $description->type = ObjectType::_INTERFACE();
        }

        $description->path            = $path;
        $description->name            = $object->namespacedName->toString();
        $description->stmts           = $stmts;
        $description->reflectionClass = new ReflectionClass($description->name);

        return $description;
    }

    public function __toString()
    {
        return $this->name;
    }
}
