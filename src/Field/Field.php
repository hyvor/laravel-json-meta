<?php

namespace Hyvor\JsonMeta\Field;

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


    public function __construct(public string $name)
    {}

    /**
     * @return self<T | null>
     */
    public function nullable() : self
    {
        $this->nullable = true;
        return $this;
    }

    /**
     * @param T $default
     * @return self<T>
     */
    public function default($default) : self
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @param mixed $value
     * @return T
     */
    public function get($value)
    {
        if ($this->nullable && $value === null) {
            /** @var T $ret */
            $ret = null;
            return $ret;
        }

        return $this->getCastedValue($value);
    }


    /**
     * @param mixed $value
     * @return T
     */
    abstract protected function getCastedValue($value);

}