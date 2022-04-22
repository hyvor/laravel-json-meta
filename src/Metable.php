<?php
namespace Hyvor\JsonMeta;

use stdClass;

trait Metable
{

    private Definer $metaDefiner;

    public function getMeta(string $name) : mixed
    {

        $this->ensureDefiner();

        if (!$this->metaDefiner->has($name)) {
            throw new MetableException("Undefined meta key: $name");
        }

        $meta = $this->getMetaFromTable();

        $definition = $this->metaDefiner->get($name);

        return property_exists($meta, $name) ? $meta->{$name} : $definition->getDefault();

    }

    public function getAllMeta() : object
    {

        $this->ensureDefiner();

        $ret = [];
        $meta = $this->getMetaFromTable();

        $definitions = $this->metaDefiner->getAll();
        foreach ($definitions as $name => $definition) {
            $ret[$name] = property_exists($meta, $name) ? $meta->{$name} : $definition->getDefault();
        }

        return (object) $ret;

    }

    /**
     * @throws MetableException
     */
    public function setMeta(array|string $name, $value = null)
    {

        $this->ensureDefiner();

        $metas = [];

        if (is_array($name)) {
            $metas = $name;
        } else {
            $metas[$name] = $value;
        }


        $fill = [];
        foreach ($metas as $metaName => $metaValue) {

            if (!$this->metaDefiner->has($metaName)) {
                throw new MetableException("Undefined meta key: $metaName in {$this->getTable()} table");
            }

            $definition = $this->metaDefiner->get($metaName);
            $types = $definition->getTypes();

            if (
                $types !== null &&
                !Validator::validate($types, $metaValue)
            ) {
                throw new MetableException(
                    "Invalid value type for $metaName in {$this->getTable()} table"
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


    private function ensureDefiner()
    {
        if (!isset($this->metaDefiner)) {
            $definer = new Definer;
            $this->metaDefinition($definer);
            $this->metaDefiner = $definer;
        }
    }

    /**
     * This function is written to get meta from table
     * regardless of the casts used in the model
     * supports string, array, and object
     */
    private function getMetaFromTable() : object
    {
        $meta = $this->meta;

        if (is_string($meta)) {
            return json_decode($meta) ?? new stdClass;
        } else if (is_array($meta)) {
            return (object) $meta;
        } else if (is_object($meta)) {
            return $meta;
        }

        return new stdClass;
    }

}