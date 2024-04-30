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

        $this->ensureMetaDefinition();

        $metaFromTable = $this->getMetaFromTable();
        $ret = [];

        $fields = $this->metaDefinition->getFields();
        foreach ($fields as $name => $field) {
            $ret[$name] = array_key_exists($name, $metaFromTable) ? $field->get($metaFromTable[$name]) : $field->getDefault();
        }

        return (object) $ret;

    }

    /**
     * Get meta from table regardless of the casts used in the model
     * supports string, array, and object
     * @return array<string, mixed>
     */
    private function getMetaFromTable() : array
    {

        $meta = $this->meta;

        if (is_string($meta)) {
            return json_decode($meta, true) ?? [];
        } else if (is_array($meta)) {
            return $meta;
        } else if (is_object($meta)) {
            return (array)$meta;
        }

        return [];

    }

    public function setMeta(array $data) : self
    {




        return $this;

    }


    private function ensureMetaDefinition()
    {

        if (!isset($this->metaDefinition)) {
            $definition = new MetaDefinition;
            $this->defineMeta($definition);
            $this->metaDefinition = $definition;
        }

    }

}