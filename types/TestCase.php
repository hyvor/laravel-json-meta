<?php

use function PHPStan\dumpType;

class TestCase
{

    public function __construct()
    {

        $model = new TestModel;
        $name = $model->meta('name');
        $isOn = $model->meta('is_on');

        dumpType($name);
        dumpType($isOn);

    }

}