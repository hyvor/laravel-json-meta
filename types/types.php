<?php

use Hyvor\JsonMeta\Tests\TestModel;

use Hyvor\JsonMeta\Tests\TestModelAdvanced;

use function PHPStan\Testing\assertType;

$model = new TestModel;

assertType(
    "array{name: string|null, posts_count: int, is_active: bool, rating: float, status: 'active'|'inactive', comments: 'Hyvor\\\\JsonMeta\\\\Tests\\\\CommentsTypeEnum'}",
    $model->metaGetAll()
);

assertType('string|null', $model->metaGet('name'));
assertType('int', $model->metaGet('posts_count'));
assertType('bool', $model->metaGet('is_active'));
assertType('float', $model->metaGet('rating'));
assertType('\'active\'|\'inactive\'', $model->metaGet('status'));
assertType("'Hyvor\\\\JsonMeta\\\\Tests\\\\CommentsTypeEnum'", $model->metaGet('comments'));


// test with non-constant values
// to make sure static resolving works

$advancedModel = new TestModelAdvanced();

assertType(
    "array{name: string|null, comments: 'Hyvor\\\\JsonMeta\\\\Tests\\\\CommentsTypeEnum'}",
    $advancedModel->metaGetAll()
);