<?php

namespace Hyvor\JsonMeta;

class Definition
{

    public string $name;

    /**
     * @var string[]
     */
    public array $types;

    public mixed $default;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string|string[] $type
     * @return self
     */
    public function type(string|array $types) : self
    {
        if (is_string($types)) {
            $types = explode('|', $types);
        }

        $this->types = $types;

        return $this;
    }

    public function default(mixed $default) : self
    {
        $this->default = $default;
        return $this;
    }

}