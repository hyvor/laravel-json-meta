<?php

namespace Hyvor\JsonMeta\Field;

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
        private $enum
    ) {}

    public function getCastedValue($value)
    {

        if (is_string($this->enum)) {
            return $this->enum::from($value);
        } else {
            return $value;
        }

    }

}
