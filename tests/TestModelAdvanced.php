<?php

namespace Hyvor\JsonMeta\Tests;

use Hyvor\JsonMeta\HasMeta;
use Hyvor\JsonMeta\MetaDefinition;
use Illuminate\Database\Eloquent\Model;

class TestModelAdvanced extends Model
{

    use HasMeta;

    public function defineMeta(MetaDefinition $meta) : void
    {

        $meta->string('nam' . 'e')->nullable();
        $meta->enum('comments', $this->getEnum())->default(CommentsTypeEnum::HYVOR);

    }

    /**
     * @return 'Hyvor\JsonMeta\Tests\CommentsTypeEnum'
     */
    private function getEnum() : string
    {
        return CommentsTypeEnum::class;
    }

}