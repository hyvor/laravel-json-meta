<?php

namespace Hyvor\JsonMeta\Field;

/**
 * @extends Field<boolean>
 */
class BooleanField extends Field
{

    public function getCastedValue($value) : bool
    {
        return boolval($value);
    }

    public function validateValue($value): bool
    {
        return is_bool($value);
    }

}