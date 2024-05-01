<?php

namespace Hyvor\JsonMeta\Field;

use ValueError;

use function PHPStan\dumpType;

/**
 * @template FieldT
 * @extends Field<FieldT>
 */
class EnumField extends Field
{

    /**
     * @param FieldT $enum
     */
    public function __construct(
        public string $name,
        public $enum
    ) {}

    public function getCastedValue($value)
    {

        if (is_string($this->enum) && enum_exists($this->enum)) {
            try {
                return $this->enum::from($value);
            } catch (ValueError) {
                throw new \Exception("Value $value is not in the enum $this->name");
            }
        } else {
            if (!in_array($value, $this->enum)) {
                throw new \Exception("Value $value is not in the enum $this->name");
            }
            return $value;
        }

    }



    public function validateValue($value): bool
    {

        if (is_string($this->enum) && enum_exists($this->enum)) {
            return $value instanceof $this->enum;
        } else {
            return in_array($value, $this->enum);
        }

    }

}
