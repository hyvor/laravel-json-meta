<?php

namespace Hyvor\JsonMeta\Tests\Unit;

use Hyvor\JsonMeta\Field\BooleanField;
use Hyvor\JsonMeta\Field\EnumField;
use Hyvor\JsonMeta\Field\FloatField;
use Hyvor\JsonMeta\Field\IntegerField;
use Hyvor\JsonMeta\Field\StringField;
use Hyvor\JsonMeta\MetaDefinition;
use Hyvor\JsonMeta\Tests\CommentsTypeEnum;

it('adds a string field', function() {

    $meta = new MetaDefinition();
    $meta->string('name');

    $fields = $meta->getFields();
    expect($fields)->toHaveCount(1);
    expect($fields['name'])->toBeInstanceOf(StringField::class);

});

it('adds an integer field', function() {

    $meta = new MetaDefinition();
    $meta->integer('age');

    $fields = $meta->getFields();
    expect($fields)->toHaveCount(1);
    expect($fields['age'])->toBeInstanceOf(IntegerField::class);

});

it('adds a float field', function() {

    $meta = new MetaDefinition();
    $meta->float('price');

    $fields = $meta->getFields();
    expect($fields)->toHaveCount(1);
    expect($fields['price'])->toBeInstanceOf(FloatField::class);

});

it('adds a boolean field', function() {

    $meta = new MetaDefinition();
    $meta->boolean('is_active');

    $fields = $meta->getFields();
    expect($fields)->toHaveCount(1);
    expect($fields['is_active'])->toBeInstanceOf(BooleanField::class);

});

it('adds an enum field', function() {

    $meta = new MetaDefinition();
    $meta->enum('status', ['active', 'inactive']);

    $fields = $meta->getFields();
    expect($fields)->toHaveCount(1);
    expect($fields['status'])->toBeInstanceOf(EnumField::class);
    expect($fields['status']->enum)->toBe(['active', 'inactive']);

});

it('adds an enum field with enum', function() {

    $meta = new MetaDefinition();
    $meta->enum('comments_type', CommentsTypeEnum::class);

    $fields = $meta->getFields();
    expect($fields)->toHaveCount(1);
    expect($fields['comments_type'])->toBeInstanceOf(EnumField::class);
    expect($fields['comments_type']->enum)->toBe(CommentsTypeEnum::class);

});