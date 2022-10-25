<?php

namespace Hyvor\JsonMeta;

trait HasMeta
{

    private MetaDefinition $metaBlueprint;

    /**
     * @param string|array{string: mixed} $name
     * @param string|null $value
     * @return mixed
     */
    public function meta(string|array $name, string $value = null) : mixed
    {

        if (is_array($name)) {
            return $this->setMeta($name);
        }

        if (func_num_args() === 2) {
            return $this->setMeta([$name => $value]);
        }

        return $this->getMeta($name);

    }

    public function getMeta(string $name)
    {

    }

    public function setMeta(array $data) : self
    {



        return $this;

    }

}