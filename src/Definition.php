<?php

namespace Hyvor\JsonMeta;

class Definition
{

    private string $name;

    /**
     * @var string[]
     */
    private ?array $types = null;

    private mixed $default = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string|string[] $types
     * @return self
     */
    public function type(string|array $types) : self
    {
        if (is_string($types)) {
            $types = explode('|', $types);
        }

        foreach ($types as $type) {
            if (!Validator::validateType($type)) {
                throw new MetableException("Unsupported type: $type in meta definition");
            }
        }

        $this->types = $types;

        return $this;
    }

    public function default(mixed $default) : self
    {
        $this->default = $default;
        return $this;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getDefault() : mixed
    {
        return $this->default;
    }

    /**
     * @return string[]|null
     */
    public function getTypes() : ?array
    {
        return $this->types;
    }

}