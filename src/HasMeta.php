<?php

namespace Hyvor\JsonMeta;

trait HasMeta
{

    private MetaDefinition $metaDefinition;

    /**
     * @template T of key-of<ModelMeta<self>>
     * @param T $name
     * @return ModelMeta<self>[T]
     */
    public function metaGet(string $name)
    {

        $this->ensureMetaDefinition();

        $fields = $this->metaDefinition->getFields();

        if (!isset($fields[$name])) {
            throw new MetaException("Field $name is not defined in the meta definition");
        }

        $metaFromTable = $this->getMetaFromTable();

        return $fields[$name]->getFromTableMeta($metaFromTable);

    }

    /**
     * @return ModelMeta<self>
     */
    public function metaGetAll() : mixed
    {

        $this->ensureMetaDefinition();

        $metaFromTable = $this->getMetaFromTable();
        $ret = [];

        $fields = $this->metaDefinition->getFields();
        foreach ($fields as $name => $field) {
            $ret[$name] = $field->getFromTableMeta($metaFromTable);
        }

        return $ret;

    }


    /**
     * @template T of key-of<ModelMeta<self>>
     * @param ModelMeta<self, true>|T $data
     * @param ModelMeta<self>[T] $value
     */
    public function metaSet(string|array $data, $value = null) : void
    {

        $this->ensureMetaDefinition();

        if (!is_null($value)) {
            $data = [$data => $value];
        }

        $fill = [];

        foreach ($data as $metaName => $metaValue) {

            if (!$this->metaDefinition->hasField($metaName)) {
                throw new MetaException("Field `$metaName` is not defined in the meta definition of {$this->getTable()}");
            }

            $field = $this->metaDefinition->getField($metaName);

            if ($field->validate($metaValue) === false) {
                throw new MetaException(
                    "Invalid value type for meta `$metaName` in {$this->getTable()} table"
                );
            }

            $fill["meta->$metaName"] = $metaValue;

        }


        /**
         * ->update() does not work unless unguarded
         * There's no reason to guard meta
         */
        $this->forceFill($fill)->save();

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

    private function ensureMetaDefinition() : void
    {

        if (!isset($this->metaDefinition)) {
            $definition = new MetaDefinition;
            $this->defineMeta($definition);
            $this->metaDefinition = $definition;
        }

    }

    abstract public function defineMeta(MetaDefinition $meta) : void;

}