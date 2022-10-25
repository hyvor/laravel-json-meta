<?php

namespace Hyvor\JsonMeta\Tests;

use Definer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Metable;

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