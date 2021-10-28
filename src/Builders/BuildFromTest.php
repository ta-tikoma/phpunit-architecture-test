<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Builders;

use Closure;
use PHPUnit\Architecture\Elements\Layer;

trait BuildFromTest
{
    /**
     * @param string|string[] $include start of namespace for include to layer
     * @param string|string[] $exclude start of namespace for exclude from layer
     */
    public function layerFromNamespace($include, $exclude = []): Layer
    {
        return LayerBuilder::fromNamespace($include, $exclude);
    }

    /**
     * @param string|string[] $include directory for include to layer
     * @param string|string[] $exclude directory for exclude from layer
     */
    public function layerFromDirectory($include, $exclude = []): Layer
    {
        return LayerBuilder::fromDirectory($include, $exclude);
    }

    /**
     * Regex must contains group with name 'layer'
     *
     * @return Layer[]
     */
    public function layersFromNamespaceRegex(string $namespaceRegex): array
    {
        return LayersBuilder::fromNamespaceRegex($namespaceRegex);
    }

    /**
     * @param Closure $closure Contract: static function (ObjectDescription $objectNames): ?string
     * @return Layer[]
     */
    public function layersFromClosure(Closure $closure): array
    {
        return LayersBuilder::fromClosure($closure);
    }
}
