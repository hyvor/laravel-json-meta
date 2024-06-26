<?php

namespace Hyvor\JsonMeta\PHPStan;

use PhpParser\Node;
use PHPStan\Analyser\NameScope;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Analyser\ScopeFactory;
use PHPStan\DependencyInjection\Container;
use PHPStan\Parser\Parser;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\FloatType;
use PHPStan\Type\Generic\GenericClassStringType;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Arg;
use PHPStan\Type\UnionType;

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
    public function getTypes(ClassReflection $classReflection) : array
    {

        $fileName = $classReflection->getFileName();

        if (!$fileName)
            return [];

        $fileNodes = $this->parser->parseFile($fileName);

        /** @var NodeScopeResolver $nodeScopeResolver */
        $nodeScopeResolver = $this->container->getByType(NodeScopeResolver::class);

        /** @var ScopeFactory $scopeFactory */
        $scopeFactory = $this->container->getByType(ScopeFactory::class);

        $classNode = ParserHelper::findClassNode(
            $classReflection->getName(),
            $fileNodes
        );

        if ($classNode === null)
            return [];

        $methodNode = ParserHelper::findMethodNode('defineMeta', $classNode->stmts);

        if ($methodNode === null || $methodNode->stmts === null)
            return [];

        // find param name (field key)
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


        $methodScope = $scopeFactory->create(ScopeContext::create($fileName))
            ->enterClass($classReflection)
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

                if (!$node instanceof MethodCall)
                    return;

                if (
                    $node->var instanceof Variable &&
                    $node->var->name === $paramName &&

                    // first arg is always the string key for all methods
                    $node->args[0] instanceof Arg // &&
                    //$node->args[0]->value instanceof String_
                ) {

                    $argType = $scope->getType($node->args[0]->value);
                    $constantStrings = $argType->getConstantStrings();

                    $key = $constantStrings[0]->getValue();
                    $type = $this->getTypeFromDefinitionCall($node, $scope);

                    if ($type && !isset($types[$key])) {
                        $types[$key] = $type;
                    }

                    return;
                }

                if (
                    $node->name instanceof Identifier &&
                    $node->name->name === 'nullable'
                ) {

                    // $node is now nullable() method call

                    // go up the chain to find the original method call
                    $check = $node->var;

                    while ($check instanceof MethodCall) {

                        if (
                            $check->var instanceof Variable &&
                            $check->var->name === $paramName &&
                            $check->args[0] instanceof Arg
                        ) {

                            $argType = $scope->getType($check->args[0]->value);
                            $constantStrings = $argType->getConstantStrings();

                            $key = $constantStrings[0]->getValue();
                            $type = $this->getTypeFromDefinitionCall($check, $scope);

                            if (!$type)
                                return;

                            $types[$key] = new UnionType([$type, new NullType]);

                            return;
                        }

                        $check = $check->var;

                    }

                }

            }
        );

        return $types;

    }

    private function getTypeFromDefinitionCall(MethodCall $node, Scope $scope) : ?Type
    {
        
        if (!$node->name instanceof Identifier)
            return null;

        $methodName = $node->name->name;

        $type = match ($methodName) {

            'string' => new StringType,
            'integer' => new IntegerType,
            'float' => new FloatType,
            'boolean' => new BooleanType,
            'enum' => $this->getEnumTypeFromMethodCall($node, $scope),

            default => null

        };

        if (!$type)
            return null;

        return $type;

    }

    private function getEnumTypeFromMethodCall(MethodCall $node, Scope $scope) : ?Type
    {
        if (count($node->args) !== 2)
            return null;

        $enumArg = $node->args[1];

        if (!$enumArg instanceof Arg)
            return null;

        $argType = $scope->getType($enumArg->value);

        if ($argType instanceof ConstantArrayType) {

            $types = [];

            foreach ($argType->getValueTypes() as $type) {
                $types[] = $type;
            }

            return new UnionType($types);
        }

        if (
            $argType instanceof ConstantStringType &&
            $argType->isClassStringType()->yes()
        ) {
            return new ConstantStringType($argType->getValue(), true);
        }

        return null;
    }

}