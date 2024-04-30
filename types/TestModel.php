<?php

use Hyvor\JsonMeta\MetaDefinition;
use Hyvor\JsonMeta\HasMeta;
use Illuminate\Database\Eloquent\Model;
use function PHPStan\dumpType;
use function PHPStan\Testing\assertType;

enum TestEnum {
    case HELLO;
}

class TestModel extends Model
{

    use HasMeta;

    public function defineMeta(MetaDefinition $meta) : void
    {

        $meta
            ->string('name')
            ->nullable()
            ->default(null);

        $meta
            ->boolean('is_on')
            ->default(false);

        /*$ret = $meta->enum('value', TestCase::class)->default(TestCase::HELLO);
        dumpType($ret);*/
    }

    public function somethingElse() : bool
    {
        return false;
    }

}