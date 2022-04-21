<?php

namespace Hyvor\JsonMeta;

class Definer
{
    /**
     * @var array{string: Definition}
     */
    public array $definitions;

    public function add(string $name) : Definition
    {

        $definition = new Definition($name);
        $this->definitions[$name] = $definition;

        return $definition;

    }

}