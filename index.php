<?php
include_once './vendor/autoload.php';

use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PHPStan\Parser\CachedParser;
use PHPStan\Parser\SimpleParser;


$mainParser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
$simpleParser = new SimpleParser($mainParser, new NameResolver);

$parser = new CachedParser($simpleParser, 0);

$stmts = $parser->parseFile('types/TestModel.php');

dd($stmts);