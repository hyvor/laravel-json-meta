<?php

namespace Hyvor\JsonMeta\Tests;

use Hyvor\JsonMeta\HasMeta;
use Hyvor\JsonMeta\MetaDefinition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{

    use HasFactory;
    use HasMeta;

    protected $table = 'test_table';

    public function defineMeta(MetaDefinition $meta) : void
    {
        $meta->string('option_1')->nullable();
        $meta->integer('option_2')->nullable()->default(20);
    }

    protected static function newFactory() : TestModelFactory
    {
        return TestModelFactory::new();
    }

}