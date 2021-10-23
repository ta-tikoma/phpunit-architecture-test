<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Elements;

use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;

final class ObjectDescription
{
    private string $path;

    private string $name;

    private ?array $uses = null;

    public function __construct(string $name, string $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    private function load(): void
    {
        $content = file_get_contents($this->path);

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $nodeFinder = new NodeFinder;

        $ast = $parser->parse($content);

        $nameResolver = new NameResolver();
        $nodeTraverser = new NodeTraverser;
        $nodeTraverser->addVisitor($nameResolver);

        // Resolve names
        $stmts = $nodeTraverser->traverse($ast);

        /** @var Node\Stmt\UseUse[] $useUses */
        $useUses = $nodeFinder->findInstanceOf($stmts, Node\Stmt\UseUse::class);

        $this->uses = array_map(static function (Node\Stmt\UseUse $useUse): string {
            return $useUse->name->toCodeString();
        }, $useUses);
        // var_dump($this->uses);

        /** @var Node\Stmt\UseUse[] $useUses */
        // $classes = $nodeFinder->findInstanceOf($ast, Node\Stmt\Trait_::class);
        // var_dump($classes);
        // var_dump($this->path);
        // die();
    }

    public function getName(): string
    {
        if ($this->name === null) {
            $this->load();
        }

        return $this->name;
    }

    public function getUses(): array
    {
        if ($this->uses === null) {
            $this->load();
        }

        return $this->uses;
    }
}
