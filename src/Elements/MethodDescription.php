<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Elements;

use PhpParser\Node;

/**
 * Method description
 */
final class MethodDescription
{
    public string $name;

    /**
     * Names and types function arguments
     *
     * @var array[]
     */
    public array $args;

    /**
     * Return type
     */
    public ?string $return;

    /**
     * Line count of method
     */
    public int $size;

    public static function make(Node\Stmt\ClassMethod $classMethod): self
    {
        $description = new static();

        $description->name = $classMethod->name->toString();
        $description->args = array_map(static function (Node\Param $param): array {
            return [
                method_exists($param->type, 'toString') ? $param->type->toString() : null,
                $param->var === null ? null : $param->var->name
            ];
        }, $classMethod->params);
        $description->return = method_exists($classMethod->returnType, 'toString')
            ? $classMethod->returnType->toString()
            : null;
        $description->size = $classMethod->getEndLine() - $classMethod->getStartLine();

        return $description;
    }
}
