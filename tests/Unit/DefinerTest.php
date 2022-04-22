<?php

namespace Hyvor\JsonMeta\Tests\Unit;

use Hyvor\JsonMeta\Definer;
use Hyvor\JsonMeta\Definition;

it('adds and returns definition', function() {

    $definer = new Definer();
    $definition = $definer->add('test');
    $this->assertInstanceOf(Definition::class, $definition);

});

it('asserts has returns correct values', function() {

    $definer = new Definer();

    $definer->add('test');

    $this->assertTrue($definer->has('test'));
    $this->assertFalse($definer->has('invalid'));

});

it('gets the correct definition', function() {

    $definer = new Definer();

    $definer->add('test');

    $definition = $definer->get('test');

    $this->assertInstanceOf(Definition::class, $definition);
    $this->assertEquals($definition->getName(), 'test');

});

it('gets all', function() {

    $definer = new Definer();

    $definer->add('test');
    $definer->add('test2');

    $definitions = $definer->getAll();

    $this->assertEquals(count($definitions), 2);
    $this->assertObjectHasAttribute('name', $definitions['test']);

});