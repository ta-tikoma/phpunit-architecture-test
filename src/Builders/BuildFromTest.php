<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Builders;

use Closure;
use PHPUnit\Architecture\Elements\Layer;

trait BuildFromTest
{
    /**
     * @param string|string[] $include start of namespace for include to layer
     */
    public function layerFromNameStart($include): Layer
    {
        $include = is_array($include) ? $include : [$include];

        $builder = (new LayerBuilder);
        foreach ($include as $start) {
            $builder = $builder->includeNameStart($start);
        }

        return $builder->build();
    }

    /**
     * @param string|string[] $include directory for include to layer
     */
    public function layerFromPath($include): Layer
    {
        $include = is_array($include) ? $include : [$include];

        $builder = (new LayerBuilder);
        foreach ($include as $start) {
            $builder = $builder->includePath($start);
        }

        return $builder->build();
    }

    /**
     * Regex must contains group with name 'layer'
     *
     * @return Layer[]
     */
    public function layersFromNameRegex(string $namespaceRegex): array
    {
        return LayersBuilder::fromNameRegex($namespaceRegex);
    }

    /**
     * @param Closure $closure Contract: static function (ObjectDescription $objectNames): ?string
     * @return Layer[]
     */
    public function layersFromClosure(Closure $closure): array
    {
        return LayersBuilder::fromClosure($closure);
    }

    /**
     * Access to layer builder
     */
    public function layer(): LayerBuilder
    {
        return new LayerBuilder;
    }
}
