<?php

namespace Hyvor\JsonMeta\Tests\Unit;

use Exception;
use Hyvor\JsonMeta\Field\BooleanField;
use Hyvor\JsonMeta\Field\EnumField;
use Hyvor\JsonMeta\Field\FloatField;
use Hyvor\JsonMeta\Field\IntegerField;
use Hyvor\JsonMeta\Field\StringField;
use Hyvor\JsonMeta\Tests\CommentsTypeEnum;

it('get default', function() {
    $field = new BooleanField('is_active');
    $field->default(false);
    expect($field->name)->toBe('is_active');
    expect($field->getDefault())->toBe(false);
});

it('get from table meta', function() {
    $field = new BooleanField('is_active');
    $field->default(false);
    expect($field->getFromTableMeta([]))->toBe(false);
    expect($field->getFromTableMeta(['is_active' => true]))->toBe(true);
    expect($field->getFromTableMeta(['is_active' => false]))->toBe(false);
});

it('get from table meta nullable', function() {
    $field = (new StringField('name'))->nullable();
    expect($field->getFromTableMeta([]))->toBe(null);
    expect($field->getFromTableMeta(['name' => 'HYVOR']))->toBe('HYVOR');
    expect($field->getFromTableMeta(['name' => null]))->toBe(null);
});

it('throws an error if default is accessed without setting it', function() {
    $field = new BooleanField('is_active');
    $field->getDefault();
})->throws(Exception::class, 'Default value is not set for the meta field is_active');

it('boolean field', function() {

    $field = new BooleanField('is_active');
    expect($field->getCastedValue('1'))->toBe(true);
    expect($field->getCastedValue('0'))->toBe(false);
    expect($field->getCastedValue('true'))->toBe(true);
    expect($field->getCastedValue(false))->toBe(false);
    expect($field->getCastedValue(true))->toBe(true);

});

it('string field', function() {

    $field = new StringField('name');
    expect($field->getCastedValue('HYVOR'))->toBe('HYVOR');
    expect($field->getCastedValue(''))->toBe('');
    expect($field->getCastedValue(null))->toBe('');
    expect($field->getCastedValue(123))->toBe('123');

});

it('float field', function() {

    $field = new FloatField('price');
    expect($field->getCastedValue('12.34'))->toBe(12.34);
    expect($field->getCastedValue(''))->toBe(0.0);
    expect($field->getCastedValue(null))->toBe(0.0);
    expect($field->getCastedValue(123))->toBe(123.0);

});

it('integer field', function() {

    $field = new IntegerField('price');
    expect($field->getCastedValue('12.34'))->toBe(12);
    expect($field->getCastedValue(''))->toBe(0);
    expect($field->getCastedValue(null))->toBe(0);
    expect($field->getCastedValue(123))->toBe(123);

});

it('enum field', function() {

    $field = new EnumField('status', ['active', 'inactive']);
    expect($field->getCastedValue('active'))->toBe('active');
    expect($field->getCastedValue('inactive'))->toBe('inactive');

});

it('enum field - wrong value', function() {

    $field = new EnumField('status', ['active', 'inactive']);
    $field->getCastedValue('wrong');

})->throws(Exception::class, 'Value wrong is not in the enum status');


it('enum field with enum class', function() {

    $field = new EnumField('status', CommentsTypeEnum::class);
    expect($field->getCastedValue('hyvor'))->toBe(CommentsTypeEnum::HYVOR);
    expect($field->getCastedValue('other'))->toBe(CommentsTypeEnum::OTHER);

});

it('throws an error if enum value is not in the enum', function() {

    $field = new EnumField('status', CommentsTypeEnum::class);
    $field->getCastedValue('wrong');

})->throws(Exception::class, 'Value wrong is not in the enum status');