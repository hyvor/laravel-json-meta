<?php

namespace Hyvor\JsonMeta\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * @source https://github.com/phpstan/phpstan-doctrine/blob/1.3.x/src/Type/Doctrine/QueryBuilder/OtherMethodQueryBuilderParser.php
 */
class ParserHelper
{

    /**
     * @param Node[] $nodes
     */
    public static function findClassNode(string $className, array $nodes): ?Class_
    {
        foreach ($nodes as $node) {
            if (
                $node instanceof Class_
                && $node->namespacedName !== null
                && $node->namespacedName->toString() === $className
            ) {
                return $node;
            }

            if (
                !$node instanceof Namespace_
                && !$node instanceof Declare_
            ) {
                continue;
            }
            $subNodeNames = $node->getSubNodeNames();
            foreach ($subNodeNames as $subNodeName) {
                $subNode = $node->{$subNodeName};
                if (!is_array($subNode)) {
                    $subNode = [$subNode];
                }

                $result = self::findClassNode($className, $subNode);
                if ($result === null) {
                    continue;
                }

                return $result;
            }
        }

        return null;
    }

    /**
     * @param Stmt[] $classStatements
     */
    public static function findMethodNode(string $methodName, array $classStatements): ?ClassMethod
    {

        foreach ($classStatements as $statement) {
            if (
                $statement instanceof ClassMethod
                && $statement->name->toString() === $methodName
            ) {
                return $statement;
            }
        }

        return null;
    }


}