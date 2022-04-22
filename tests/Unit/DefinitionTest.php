<?php

namespace Hyvor\JsonMeta\Tests\Unit;

use Hyvor\JsonMeta\Definition;
use Hyvor\JsonMeta\MetableException;

it('constructs and gets name', function() {

    $def = new Definition('test');
    $this->assertEquals($def->getName(), 'test');

});

it('sets type', function() {

    $def = new Definition('test');
    $ret = $def->type('string');

    // returns self
    $this->assertInstanceOf(Definition::class, $ret);
    $this->assertEquals($ret->getTypes(), ['string']);

});

it('detects invalid types', function() {

    $this->expectException(MetableException::class);

    $def = new Definition('test');
    $def->type('harmony');

});

it('sets multi types', function() {

    $def = new Definition('test');
    $def->type('string|null');

    $this->assertEquals($def->getTypes(), ['string', 'null']);

    // or array
    $def->type(['string', 'int']);

    $this->assertEquals($def->getTypes(), ['string', 'int']);

});

it('sets default', function() {

    $def = new Definition('test');
    $ret = $def->default('default');

    $this->assertInstanceOf(Definition::class, $ret);
    $this->assertEquals($def->getDefault(), 'default');

});