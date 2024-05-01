<?php

namespace Hyvor\JsonMeta\PHPStan;

use PHPStan\Analyser\NameScope;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\PhpDoc\TypeNodeResolverAwareExtension;
use PHPStan\PhpDoc\TypeNodeResolverExtension;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ErrorType;
use PHPStan\Type\Type;

class MetaTypeExtension implements TypeNodeResolverExtension, TypeNodeResolverAwareExtension
{

    private TypeNodeResolver $typeNodeResolver;

    public function __construct(private DefineMetaParser $defineParser)
    {
    }

    public function setTypeNodeResolver(TypeNodeResolver $typeNodeResolver): void
    {
        $this->typeNodeResolver = $typeNodeResolver;
    }

    public function resolve(TypeNode $typeNode, NameScope $nameScope): ?Type
    {
        if (!$typeNode instanceof GenericTypeNode) {
            // returning null means this extension is not interested in this node
            return null;
        }

        $typeName = $typeNode->type;
        if ($typeName->name !== 'meta-of') {
            return null;
        }

        $arguments = $typeNode->genericTypes;

        if (count($arguments) === 0 || count($arguments) > 2) {
            return null;
        }

        $classType = $this->typeNodeResolver->resolve($arguments[0], $nameScope);

        $optional = false;
        if (count($arguments) === 2) {
            $optional = true;
        }

        $classReflections = $classType->getObjectClassReflections();

        if (count($classReflections) !== 1) {
            return null;
        }

        $types = $this->defineParser->getTypes($classReflections[0]);

        $arrayBuilder = ConstantArrayTypeBuilder::createEmpty();

        foreach ($types as $key => $type) {
            $arrayBuilder->setOffsetValueType(new ConstantStringType($key), $type, $optional);
        }

        return $arrayBuilder->getArray();
    }
}