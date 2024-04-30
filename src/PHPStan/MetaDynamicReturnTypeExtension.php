<?php

namespace Hyvor\JsonMeta\PHPStan;

use Hyvor\JsonMeta\HasMeta;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\StaticType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;

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

        if (!$args[0]->value instanceof String_) {
            return null;
        }

        $key = $args[0]->value->value;

        $fileName = $methodReflection->getDeclaringClass()->getFileName();

        if (!$fileName)
            return null;

        $types = $this->defineParser->getTypes($methodReflection, $scope);

        return $types[$key] ?? null;

    }

}