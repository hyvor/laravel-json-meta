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
            ->boolean('spam_detection')
            ->default(false);

        $meta
            ->integer('created_at')
            ->nullable();
    }

    public function somethingElse() : bool
    {
        return false;
    }

}