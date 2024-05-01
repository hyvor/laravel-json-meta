<?php

namespace Hyvor\JsonMeta\Field;

/**
 * @extends Field<int>
 */
class IntegerField extends Field
{

    public function getCastedValue(mixed $value) : int
    {
        return intval($value);
    }

    public function validateValue($value): bool
    {
        return is_integer($value);
    }

}