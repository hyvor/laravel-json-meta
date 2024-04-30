<?php

namespace Hyvor\JsonMeta\Field;

use Hyvor\JsonMeta\Field\Exception\InvalidFieldValueException;

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