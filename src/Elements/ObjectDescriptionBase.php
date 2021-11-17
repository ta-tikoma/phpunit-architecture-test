<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Elements;

use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Node;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PHPUnit\Architecture\Enums\ObjectType;
use ReflectionClass;

class ObjectDescriptionBase
{
    public ObjectType $type;

    public string $path;

    public string $name;

    public array $stmts;

    public ReflectionClass $reflectionClass;

    protected static ?Parser $parser = null;

    protected static NodeTraverser $nodeTraverser;

    protected static NodeFinder $nodeFinder;

    protected static function init(): void
    {
        if (self::$parser !== null) {
            return;
        }

        self::$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        self::$nodeTraverser = new NodeTraverser;
        self::$nodeTraverser->addVisitor(new NameResolver);

        self::$nodeFinder = new NodeFinder;
    }

    public static function make(string $path): ?self
    {
        self::init();

        $content = file_get_contents($path);
        $ast = self::$parser->parse($content);

        if ($ast === null) {
            return null;
        }

        $stmts = self::$nodeTraverser->traverse($ast);

        /** @var Node\Stmt\Class_|Node\Stmt\Trait_|Node\Stmt\Interface_ $object */
        $object = self::$nodeFinder->findFirst($stmts, function (Node $node) {
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
