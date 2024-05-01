<?php

namespace Hyvor\JsonMeta\Field;

/**
 * @extends Field<float>
 */
class FloatField extends Field
{

    public function getCastedValue($value) : float
    {
        return floatval($value);
    }
}