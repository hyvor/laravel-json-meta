<?php

namespace Hyvor\JsonMeta;

trait HasMeta
{

    private MetaDefinition $metaDefinition;

    /**
     * @param string|array<string, mixed> $name
     * @param string|null $value
     * @return mixed
     */
    public function meta(string|array $name = null, string $value = null) : mixed
    {

        if (is_null($name)) {
            return $this->getAllMeta();
        }

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

    public function getAllMeta() : mixed
    {

        $fromTable = $this->meta;


    }

    /**
     * @return array
     */
    private function getMetaFromTable() : array
    {



    }

    public function setMeta(array $data) : self
    {




        return $this;

    }


    private function setMetaDefinition()
    {

        if (!isset($this->metaDefinition)) {
            $definition = new MetaDefinition;
            $this->defineMeta($definition);
            $this->metaDefinition = $definition;
        }

    }

}