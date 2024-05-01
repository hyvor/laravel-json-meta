<?php

namespace Hyvor\JsonMeta\Tests\Unit;

use Hyvor\JsonMeta\MetaException;
use Hyvor\JsonMeta\Tests\CommentsTypeEnum;
use Hyvor\JsonMeta\Tests\TestModel;

it('gets defaults', function() {

    $model = new TestModel;

    expect($model->metaGet('name'))->toBeNull();
    expect($model->metaGet('posts_count'))->toBe(0);
    expect($model->metaGet('is_active'))->toBeTrue();
    expect($model->metaGet('rating'))->toBe(0.0);
    expect($model->metaGet('status'))->toBe('active');
    expect($model->metaGet('comments'))->toBe(CommentsTypeEnum::HYVOR);

});

it('gets all', function() {

    $model = new TestModel;

    expect($model->metaGetAll())->toBe([
        'name' => null,
        'posts_count' => 0,
        'is_active' => true,
        'rating' => 0.0,
        'status' => 'active',
        'comments' => CommentsTypeEnum::HYVOR,
    ]);
});

it('gets values', function() {

    $model = new TestModel([
        'meta' => json_encode([
            'name' => 'John Doe',
            'posts_count' => 10,
            'is_active' => false,
            'rating' => 4.5,
            'status' => 'inactive',
            'comments' => CommentsTypeEnum::OTHER,
        ])
    ]);

    expect($model->metaGet('name'))->toBe('John Doe');
    expect($model->metaGet('posts_count'))->toBe(10);
    expect($model->metaGet('is_active'))->toBeFalse();
    expect($model->metaGet('rating'))->toBe(4.5);
    expect($model->metaGet('status'))->toBe('inactive');
    expect($model->metaGet('comments'))->toBe(CommentsTypeEnum::OTHER);

});

it('gets values partially', function() {

    $model = new TestModel([
        'meta' => json_encode([
            'status' => 'inactive',
            'comments' => CommentsTypeEnum::OTHER,
        ])
    ]);

    expect($model->metaGet('name'))->toBeNull();
    expect($model->metaGet('posts_count'))->toBe(0);
    expect($model->metaGet('is_active'))->toBeTrue();
    expect($model->metaGet('rating'))->toBe(0.0);
    expect($model->metaGet('status'))->toBe('inactive');
    expect($model->metaGet('comments'))->toBe(CommentsTypeEnum::OTHER);

});

it('throws an error on wrong field name', function() {

    $model = new TestModel;
    $model->metaGet('wrong_field_name');

})
    ->throws(MetaException::class, 'Field wrong_field_name is not defined in the meta definition');