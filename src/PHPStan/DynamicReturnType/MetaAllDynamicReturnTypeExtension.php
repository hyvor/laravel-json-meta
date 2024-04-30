<?php

namespace Hyvor\JsonMeta\PHPStan\DynamicReturnType;

use Hyvor\JsonMeta\HasMeta;
use Hyvor\JsonMeta\PHPStan\DefineMetaParser;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

use function PHPStan\dumpType;

class MetaAllDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
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
            $methodReflection->getName() === 'getAllMeta';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): ?Type
    {

        $types = $this->defineParser->getTypes($methodReflection, $scope);

        $arrayBuilder = ConstantArrayTypeBuilder::createEmpty();

        foreach ($types as $key => $type) {
            $arrayBuilder->setOffsetValueType(new ConstantStringType($key), $type);
        }

        return $arrayBuilder->getArray();

    }

}