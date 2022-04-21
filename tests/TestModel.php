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

    public function metaDefinition(Definer $definer)
    {

        $definer->add('api_key')
            ->type('string|null')
            ->default(null);

    }

    protected static function newFactory()
    {
        return TestModelFactory::new();
    }

}