<?php

namespace Hyvor\JsonMeta\Tests\Unit;

use Hyvor\JsonMeta\MetaException;
use Hyvor\JsonMeta\Tests\TestModel;

it('sets meta', function() {

    $model = new TestModel;
    $model->metaSet('name', 'John Doe');

    expect($model->meta)->toBe(json_encode([
        'name' => 'John Doe',
    ]));

});

it('fails on invalid field', function() {

    $model = new TestModel;

    expect(function() use ($model) {
        $model->metaSet('invalid', 'John Doe');
    })->toThrow(MetaException::class, 'Field `invalid` is not defined in the meta definition of test_table');

});

it('fails on invalid type', function() {

    $model = new TestModel;

    expect(function() use ($model) {
        $model->metaSet('name', 10);
    })->toThrow(MetaException::class, 'Invalid value type for meta `name` in test_table table');

});