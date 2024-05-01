<?php

namespace Hyvor\JsonMeta\Field;

use Exception;

/**
 * @template T
 */
abstract class Field
{

    /**
     * Whether this field can contain null
     */
    private bool $nullable = false;

    /**
     * @var T
     */
    private $default;

    private bool $defaultSet = false;


    public function __construct(public string $name)
    {}

    /**
     * @return self<T | null>
     */
    public function nullable() : self
    {
        /** @var self<T | null> $copy */
        $copy = clone $this;

        $copy->nullable = true;

        if ($copy->defaultSet === false) {
            $copy->default = null;
            $copy->defaultSet = true;
        }

        return $copy;
    }

    /**
     * @param T $default
     * @return self<T>
     */
    public function default($default) : self
    {
        $this->default = $default;
        $this->defaultSet = true;
        return $this;
    }

    /**
     * @param array<string, mixed> $metaFromTable
     */
    public function getFromTableMeta(array $metaFromTable) : mixed
    {
        return array_key_exists($this->name, $metaFromTable) ?
            $this->getNullOrCasted($metaFromTable[$this->name]) :
            $this->getDefault();
    }

    /**
     * @param mixed $value
     * @return T
     */
    public function getNullOrCasted($value)
    {
        if ($this->nullable && $value === null) {
            /** @var T $ret */
            $ret = null;
            return $ret;
        }

        return $this->getCastedValue($value);
    }

    public function getDefault() : mixed
    {
        if ($this->defaultSet === false) {
            throw new Exception('Default value is not set for the meta field ' . $this->name);
        }
        return $this->default;
    }


    /**
     * @param mixed $value
     * @return T
     */
    abstract protected function getCastedValue($value);

}