<?php
namespace Hyvor\JsonMeta\Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{

    protected function setUp() : void
    {

        parent::setUp();

        $this->app['db']->connection()->getSchemaBuilder()->create('test_table', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->json('meta')->nullable();
        });

    }

}