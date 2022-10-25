<?php

use function PHPStan\dumpType;

class TestCase
{

    public function __construct()
    {

        $model = new TestModel;
        $model->meta('name');

        $x = $model->meta(['name' => 'wow']);
        dumpType($x);

    }

}