<?php

use Hyvor\JsonMeta\Tests\TestModel;

it('works', function() {

    $model = TestModel::factory()->make();
    $model->getDefinitions();
    dd($model);

});