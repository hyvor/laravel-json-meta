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

    protected $fillable = ['meta'];

    public function defineMeta(MetaDefinition $meta) : void
    {

        $meta->string('name')->nullable();
        $meta->integer('posts_count')->default(0);
        $meta->boolean('is_active')->default(true);
        $meta->float('rating')->default(0.0);
        $meta->enum('status', ['active', 'inactive'])->default('active');
        $meta->enum('comments', CommentsTypeEnum::class)->default(CommentsTypeEnum::HYVOR);

    }

    protected static function newFactory() : TestModelFactory
    {
        return TestModelFactory::new();
    }

}