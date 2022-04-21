<?php

namespace Hyvor\JsonMeta;

class Validator
{

    public static function validate(array $types, mixed $value) : bool
    {

        $valid = false;

        foreach ($types as $type) {

            if ($type === 'string' && is_string($value)) {
                $valid = true;
                break;
            }

            if ($type === 'int' && is_int($value)) {
                $valid = true;
                break;
            }

            if ($type === 'float' && is_float($value)) {
                $valid = true;
                break;
            }

            if ($type === 'bool' && is_bool($value)) {
                $valid = true;
                break;
            }

            if ($type === 'null' && is_null($value)) {
                $valid = true;
                break;
            }

            if (preg_match('/^enum:(.+)$/', $type, $matches)) {
                $validValues = explode(',', $matches[1]);
                if (in_array($value, $validValues)) {
                    $valid = true;
                    break;
                }
            }

        }

        return $valid;

    }

}