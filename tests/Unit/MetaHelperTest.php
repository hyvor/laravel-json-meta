<?php

namespace Hyvor\JsonMeta\Tests\Unit;

use Hyvor\JsonMeta\MetaDefinition;
use Hyvor\JsonMeta\Tests\TestModel;

it('gets definitions', function() {

    $model = new TestModel();
    expect($model->metaGetDefinition())->toBeInstanceOf(MetaDefinition::class);

});

it('gets field names', function() {

    $model = new TestModel();
    expect($model->metaGetFieldNames())->toBeArray();
    expect($model->metaGetFieldNames())->toBe([
        0 => 'name',
        1 => 'posts_count',
        2 => 'is_active',
        3 => 'rating',
        4 => 'status',
        5 => 'comments',
    ]);

});