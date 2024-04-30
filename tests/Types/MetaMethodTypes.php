<?php

namespace Hyvor\JsonMeta\Tests\Types;

use function PHPStan\Testing\assertType;

class MetaMethodTypes
{

    public function withString()
    {
        $model = new TypeTestModel;

        assertType(
            'string',
            $model->meta('name')
        );

    }

}