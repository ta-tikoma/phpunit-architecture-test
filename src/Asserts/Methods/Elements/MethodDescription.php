<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Methods\Elements;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use PhpParser\Node;
use PHPUnit\Architecture\Asserts\Methods\ObjectMethodsDescription;
use PHPUnit\Architecture\Services\ServiceContainer;

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
     *
     * @var string|array|null
     */
    public $return;

    /**
     * Line count of method
     */
    public int $size;

    public static function make(ObjectMethodsDescription $objectMethodsDescription, Node\Stmt\ClassMethod $classMethod): self
    {
        $description = new static();


        $docComment = (string) $classMethod->getDocComment();

        try {
            $docBlock = ServiceContainer::$docBlockFactory->create(empty($docComment) ? '/** */' : $docComment);
        } catch (\RuntimeException $e) {
            $docBlock = ServiceContainer::$docBlockFactory->create('/** */');
        }

        $description->name = $classMethod->name->toString();
        $description->args = self::getArgs($objectMethodsDescription, $classMethod, $docBlock);
        $description->return = self::getReturnType($objectMethodsDescription, $classMethod, $docBlock);
        $description->size = $classMethod->getEndLine() - $classMethod->getStartLine();

        return $description;
    }

    private static function getArgs(
        ObjectMethodsDescription $objectMethodsDescription,
        Node\Stmt\ClassMethod $classMethod,
        DocBlock $docBlock
    ): array {
        /** @var Param[] $tags */
        $tags = $docBlock->getTagsWithTypeByName('param');

        return array_map(static function (Node\Param $param) use ($tags, $objectMethodsDescription): array {
            $name = $param->var === null ? null : $param->var->name;
            $type = self::tryToString($param->type);

            if ($type === null) {
                foreach ($tags as $tag) {
                    if ($tag->getVariableName() === $name && $tag->getType() !== null) {
                        $type = $objectMethodsDescription->getDocBlockTypeWithNamespace($tag->getType());
                        break;
                    }
                }
            }

            return [$type, $name];
        }, $classMethod->params);
    }

    private static function tryToString($object)
    {
        if ($object !== null) {
            if (method_exists($object, 'toString')) {
                return $object->toString();
            }
        }

        return null;
    }

    private static function getReturnType(
        ObjectMethodsDescription $objectMethodsDescription,
        Node\Stmt\ClassMethod $classMethod,
        DocBlock $docBlock
    ) {
        $type = self::tryToString($classMethod->returnType);
        if ($type !== null) {
            return $type;
        }

        /** @var Return_[] $tags */
        $tags = $docBlock->getTagsWithTypeByName('return');
        if ($tag = array_shift($tags)) {
            return $objectMethodsDescription->getDocBlockTypeWithNamespace($tag->getType());
        }

        return null;
    }

    public function __toString()
    {
        return $this->name;
    }
}
