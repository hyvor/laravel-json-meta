<?php

use function PHPStan\dumpType;

$model = new TestModel;
$name = $model->meta('name');
$isOn = $model->meta('is_on');

dumpType($name);
dumpType($isOn);

dumpType($model->getAllMeta());