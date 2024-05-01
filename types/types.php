<?php

use function PHPStan\dumpType;

$model = new TestModel;
$name = $model->metaGet('name');
$isOn = $model->metaGet('spam_detection');

dumpType($name);
dumpType($isOn);

dumpType($model->metaGetAll());

$model->metaSet('name', 130);