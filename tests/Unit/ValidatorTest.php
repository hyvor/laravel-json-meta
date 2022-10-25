<?php

namespace Hyvor\JsonMeta\Tests\Unit;

use Validator;

it('validates strings', function() {

    $types = ['string'];
    $this->assertTrue( Validator::validate($types, 'hello') );
    $this->assertFalse( Validator::validate($types, 20) );

});

it('validates int', function() {

    $types = ['int'];
    $this->assertTrue( Validator::validate($types, 20) );
    $this->assertFalse( Validator::validate($types, '20') );
    $this->assertFalse( Validator::validate($types, 20.2) );

});

it('validates float', function() {

    $types = ['float'];
    $this->assertTrue( Validator::validate($types, 20.2) );
    $this->assertFalse( Validator::validate($types, '20') );
    $this->assertFalse( Validator::validate($types, 20) );

});

it('validates bool', function() {

    $types = ['bool'];
    $this->assertTrue( Validator::validate($types, true) );
    $this->assertTrue( Validator::validate($types, false) );
    $this->assertFalse( Validator::validate($types, null) );
    $this->assertFalse( Validator::validate($types, 'true') );

});

it('validates null', function() {

    $types = ['null'];
    $this->assertTrue( Validator::validate($types, null) );
    $this->assertFalse( Validator::validate($types, false) );
    $this->assertFalse( Validator::validate($types, 'true') );

});

it('validates enums', function() {

    $types = ['enum:val1,val2'];
    $this->assertTrue( Validator::validate($types, 'val1') );
    $this->assertTrue( Validator::validate($types, 'val2') );
    $this->assertFalse( Validator::validate($types, 'val3') );

    // strict types are not checked
    $types = ['enum:1,2'];
    $this->assertTrue( Validator::validate($types, '1') );
    $this->assertTrue( Validator::validate($types, '2') );
    $this->assertTrue( Validator::validate($types, 1) );

    // trim works?
    $types = ['enum:1, 2'];
    $this->assertTrue( Validator::validate($types, '1') );
    $this->assertTrue( Validator::validate($types, '2') );

});

it('validates type', function() {

    $this->assertTrue(Validator::validateType(Validator::SUPPORTED_TYPES[0]));
    $this->assertFalse(Validator::validateType('harmony'));

});