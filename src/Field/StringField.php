<?php

namespace Hyvor\JsonMeta\Field;

/**
 * @extends Field<string>
 */
class StringField extends Field
{

    public function getCastedValue(mixed $value) : string
    {
        return strval($value);
    }

}