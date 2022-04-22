<?php

namespace Hyvor\JsonMeta;

class Definer
{
    /**
     * @var array<string, Definition>
     */
    private array $definitions;

    public function add(string $name) : Definition
    {

        $definition = new Definition($name);
        $this->definitions[$name] = $definition;

        return $definition;

    }


    public function has(string $name) : bool
    {
        return array_key_exists($name, $this->definitions);
    }

    public function get(string $name) : Definition
    {
        return $this->definitions[$name];
    }

    /**
     * @return array<string, Definition>
     */
    public function getAll() : array
    {
        return $this->definitions;
    }

}