<?php

use Hyvor\JsonMeta\MetableException;
use Hyvor\JsonMeta\Tests\TestModel;

it('gets default meta', function () {

    $model = new TestModel();
    $option2 = $model->getMeta('option_2');

    $this->assertEquals($option2, 20);

});

it('it gets all default meta values', function() {

    $model = new TestModel();
    $meta = $model->getAllMeta();

    $this->assertObjectHasAttribute('option_1', $meta);
    $this->assertObjectHasAttribute('option_2', $meta);

    $this->assertEquals($meta->option_1, null);
    $this->assertEquals($meta->option_2, 20);

});

it('sets meta', function() {

    $model = TestModel::create();
    $model->setMeta('option_1', 'value');

    $newModel = TestModel::find($model->id);
    $opt1 = json_decode($newModel->meta)->option_1;

    $this->assertEquals($opt1, 'value');

});

it('does not set invalid meta', function() {

    $this->expectException(MetableException::class);

    $model = TestModel::create();
    $model->setMeta('option_1', 20);

});

it('sets meta multi', function() {

    $model = TestModel::create();
    $model->setMeta([
        'option_1' => 'some other value',
        'option_2' => 50
    ]);

    $newModel = TestModel::find($model->id);
    $meta = json_decode($newModel->meta);

    $this->assertEquals($meta->option_1, 'some other value');
    $this->assertEquals($meta->option_2, 50);

});

it('sets meta without type and default', function() {


    $model = TestModel::create();

    // default is null
    $this->assertEquals(null, $model->getMeta('option_3'));

    $model->setMeta([
        'option_3' => 50
    ]);
    $newModel = TestModel::find($model->id);

    $this->assertEquals(50, $newModel->getMeta('option_3'));

});