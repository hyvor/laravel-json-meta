<?php

namespace Hyvor\JsonMeta\PHPStan;

use Hyvor\JsonMeta\HasMeta;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\Scope;
use PHPStan\DependencyInjection\Container;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use PhpParser\ParserFactory;
use PHPStan\Parser\Parser;

class MetaDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{

    public function __construct(
        private DefineMetaParser $defineParser,
    ) {}

    public function getClass(): string
    {
        return Model::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getDeclaringClass()->hasTraitUse(HasMeta::class) &&
            $methodReflection->getName() === 'meta';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): ?Type
    {

        $args = $methodCall->getArgs();

        if ($args[0]->value instanceof Array_) {
            return new StaticType($methodReflection->getDeclaringClass());
        }

        $fileName = $methodReflection->getDeclaringClass()->getFileName();

        if (!$fileName)
            return null;

        $types = $this->defineParser->getTypes($methodReflection, $scope);

        return null;

    }

}