<?php


class Validator
{

    const SUPPORTED_TYPES =
        [
            'string',
            'int',
            'float',
            'bool',
            'null',
            'enum'
        ];

    /**
     * @param string[] $types
     * @param mixed $value
     * @return bool
     */
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

    public static function validateType(string $type) : bool
    {
        $type = explode(':', $type)[0]; // for enums
        return in_array($type, self::SUPPORTED_TYPES);
    }

}