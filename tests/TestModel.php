<?php

namespace Hyvor\JsonMeta\Tests;

use Hyvor\JsonMeta\Definer;
use Hyvor\JsonMeta\Metable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{

    use HasFactory;
    use Metable;

    protected $table = 'test_table';

    public function metaDefinition(Definer $definer)
    {

        $definer->add('option_1')->type('string|null')->default(null);
        $definer->add('option_2')->type('string|int')->default(20);

        // no type checking
        $definer->add('option_3');

    }

    protected static function newFactory()
    {
        return TestModelFactory::new();
    }

}