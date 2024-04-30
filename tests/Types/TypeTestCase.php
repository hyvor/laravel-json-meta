<?php

namespace Hyvor\JsonMeta\Tests\Types;

use PHPStan\Testing\TypeInferenceTestCase;

class TypeTestCase extends TypeInferenceTestCase
{

    public static function getAdditionalConfigFiles(): array
    {
        return [
            './extension.neon'
        ];
    }

}