<?php

use Hyvor\JsonMeta\Tests\TestModel;

use function PHPStan\dumpType;
use function PHPStan\Testing\assertType;

$model = new TestModel;

dumpType($model->metaGetAll());

assertType('string|null', $model->metaGet('name'));