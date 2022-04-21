<?php
namespace Hyvor\JsonMeta;

trait Metable
{

    private array $metableDefinitions;

    public function getDefinitions()
    {
        $definer = new Definer;
        $this->metaDefinition($definer);
    }

    public function getMeta(string $name)
    {



    }

    public function getMetas(string ...$name)
    {



    }

    public function setMeta(string $name, $value)
    {

        

    }

}