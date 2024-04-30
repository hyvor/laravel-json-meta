<?php

namespace Hyvor\JsonMeta\Tests\Types;

use ReflectionClass;
use function PHPStan\Testing\assertType;

it('test', function() {

    $model = new TypeTestModel;

    $types = $this->gatherAssertTypes((new ReflectionClass(MetaMethodTypes::class))->getFileName());

    foreach ($types as $type) {
        $this->assertFileAsserts($type[0], $type[1], ...array_slice($type, 2));
    }

    assertType('string', $model->meta('name'));
    assertType('bool', $model->meta('is_on'));
    assertType('int|null', $model->meta('age'));

});

/*$model = new TypeTestModel();

assertType(
    'string',
    $model->meta('name')
);



// meta with array
assertType(
    'static(Hyvor\JsonMeta\Tests\Types\TypeTestModel)',
    $model->meta(['name' => 'test',])
);*/