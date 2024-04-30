<?php

namespace Hyvor\JsonMeta\Tests\Types;

use Hyvor\JsonMeta\HasMeta;
use Hyvor\JsonMeta\MetaDefinition;
use Illuminate\Database\Eloquent\Model;

class TypeTestModel extends Model
{

    use HasMeta;

    public function defineMeta(MetaDefinition $meta) : void
    {

        $meta
            ->string('name')
            ->default('');

        $meta
            ->boolean('is_on')
            ->default(false);

        $meta
            ->integer('age')
            ->nullable();

        /*$ret = $meta->enum('value', TestCase::class)->default(TestCase::HELLO);
        dumpType($ret);*/
    }

    public function somethingElse() : bool
    {
        return false;
    }

}