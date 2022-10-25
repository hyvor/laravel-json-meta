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

}