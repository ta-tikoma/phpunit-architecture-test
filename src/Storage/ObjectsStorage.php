<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Storage;

use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PHPUnit\Architecture\Elements\ObjectDescription;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class ObjectsStorage
{
    /**
     * @var ObjectDescription[]
     */
    private static $objectMap;

    private static function allFiles()
    {
        /** @var SplFileInfo[] $paths */
        $paths = Finder::create()
            ->files()
            ->followLinks()
            ->name('/\.php$/')
            ->in(Filesystem::getBaseDir());

        foreach ($paths as $path) {
            if ($path->isFile()) {
                yield $path->getRealPath();
            }
        }
    }

    private static function init(): void
    {
        if (self::$objectMap !== null) {
            return;
        }

        self::$objectMap = [];

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $nodeFinder = new NodeFinder;

        $nameResolver = new NameResolver();
        $nodeTraverser = new NodeTraverser;
        $nodeTraverser->addVisitor($nameResolver);

        foreach (self::allFiles() as $path) {
            $content = file_get_contents($path);
            $ast = $parser->parse($content);
            $stmts = $nodeTraverser->traverse($ast);

            /** @var Node\Stmt\Class_|Node\Stmt\Trait_|Node\Stmt\Interface_ $object */
            $object = $nodeFinder->findFirst($stmts, function (Node $node) {
                return $node instanceof Node\Stmt\Class_
                    || $node instanceof Node\Stmt\Trait_
                    || $node instanceof Node\Stmt\Interface_
                    // 
                ;
            });

            if ($object === null) {
                continue;
            }

            $name = $object->namespacedName->toCodeString();

            /** @var Node\Stmt\UseUse[] $useUses */
            $useUses = $nodeFinder->findInstanceOf($stmts, Node\Stmt\UseUse::class);
            $uses = array_map(static function (Node\Stmt\UseUse $useUse): string {
                return $useUse->name->toCodeString();
            }, $useUses);

            self::$objectMap[$name] = new ObjectDescription(
                $name,
                $path,
                $uses
            );
        }
    }

    /**
     * @return ObjectDescription[]
     */
    public static function getObjectMap(): array
    {
        self::init();

        return self::$objectMap;
    }
}
