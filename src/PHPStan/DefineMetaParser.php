<?php

namespace Hyvor\JsonMeta\PHPStan;

use PhpParser\Node;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Analyser\ScopeFactory;
use PHPStan\DependencyInjection\Container;
use PHPStan\Parser\Parser;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Type;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Expr\Variable;

class DefineMetaParser
{

    public function __construct(
        private Container $container,
        private Parser $parser
    )
    {}

    /**
     * @return array<string, Type>
     */
    public function getTypes(
        MethodReflection $methodReflection,
        Scope $scope
    ) : array
    {

        $fileName = $methodReflection->getDeclaringClass()->getFileName();

        if (!$fileName)
            return [];

        $fileNodes = $this->parser->parseFile($fileName);

        /** @var NodeScopeResolver $nodeScopeResolver */
        $nodeScopeResolver = $this->container->getByType(NodeScopeResolver::class);

        /** @var ScopeFactory $scopeFactory */
        $scopeFactory = $this->container->getByType(ScopeFactory::class);

        $classNode = ParserHelper::findClassNode(
            $methodReflection->getDeclaringClass()->getName(),
            $fileNodes
        );

        if ($classNode === null)
            return [];

        $methodNode = ParserHelper::findMethodNode('defineMeta', $classNode->stmts);

        if ($methodNode === null || $methodNode->stmts === null)
            return [];

        // find param name
        $paramName = null;

        foreach ($methodNode->params as $param) {

            if (
                $param->type &&
                $param->type instanceof FullyQualified &&
                (string) $param->type === 'Hyvor\\JsonMeta\\MetaDefinition' &&
                $param->var instanceof Variable
            ) {
                $paramName = $param->var->name;
            }

        }

        if (!$paramName)
            return [];


        $methodScope = $scopeFactory->create(
            ScopeContext::create($fileName),
            $scope->isDeclareStrictTypes(),
            [],
            $methodReflection,
            $scope->getNamespace()
        )
            ->enterClass($methodReflection->getDeclaringClass())
            ->enterClassMethod(
                $methodNode,
                TemplateTypeMap::createEmpty(),
                [],
                null,
                null,
                null,
                false,
                false,
                false
        );

        /** @var array<string, Type> $types */
        $types = [];

        $nodeScopeResolver->processNodes(
            $methodNode->stmts,
            $methodScope,
            function (Node $node, Scope $scope) use (&$types, $paramName) : void {

                if (
                    $node instanceof MethodCall &&
                    $node->var instanceof Variable &&
                    $node->var->name === $paramName
                ) {

                    $types[] = $this->getTypeFromChainedDefinitionMethodCalls($node);

                }

            }
        );

        return [];

    }

    private function getTypeFromChainedDefinitionMethodCalls(MethodCall $node) : Type
    {



    }

}