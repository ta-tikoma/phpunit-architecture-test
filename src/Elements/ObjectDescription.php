<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Elements;

use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Node;
use PhpParser\Parser;
use PhpParser\ParserFactory;

/**
 * Open to extends
 */
class ObjectDescription
{
    public string $path;

    public string $name;

    /**
     * Names of uses objects
     *
     * @var string[]
     */
    public array $uses;

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

        $name = $object->namespacedName->toCodeString();

        /** @var Node\Stmt\UseUse[] $useUses */
        $useUses = self::$nodeFinder->findInstanceOf($stmts, Node\Stmt\UseUse::class);
        $uses = array_map(static function (Node\Stmt\UseUse $useUse): string {
            return $useUse->name->toCodeString();
        }, $useUses);

        $description = new static();
        $description->path = $path;
        $description->name = $name;
        $description->uses = $uses;

        return $description;
    }
}
