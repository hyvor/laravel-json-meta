<?php

use Hyvor\JsonMeta\MetaDefinition;
use Hyvor\JsonMeta\HasMeta;
use Illuminate\Database\Eloquent\Model;
use function PHPStan\dumpType;
use function PHPStan\Testing\assertType;


class TestModel extends Model
{

    use HasMeta;

    public function defineMeta(MetaDefinition $meta) : array
    {

        $meta->string('name')->nullable()->default('string');
        /*$ret = $meta->enum('value', TestCase::class)->default(TestCase::HELLO);
        dumpType($ret);*/
    }

    public function somethingElse() : bool
    {
        return false;
    }

}