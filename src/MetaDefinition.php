<?php

namespace Hyvor\JsonMeta;

use Hyvor\JsonMeta\Field\BooleanField;
use Hyvor\JsonMeta\Field\EnumField;
use Hyvor\JsonMeta\Field\Field;
use Hyvor\JsonMeta\Field\FloatField;
use Hyvor\JsonMeta\Field\IntegerField;
use Hyvor\JsonMeta\Field\StringField;

class MetaDefinition
{

    /**
     * @var array<string, Field<mixed>>
     */
    private array $fields;

    /**
     * @return array<string, Field<mixed>>
     */
    public function getFields() : array
    {
        return $this->fields;
    }

    /**
     * @template FieldType of Field
     * @param class-string<FieldType> $fieldType
     * @return FieldType
     */
    private function addField(string $fieldType, string $name, mixed $value = null)
    {
        $field = new $fieldType(...array_slice(func_get_args(), 1));
        $this->fields[$name] = $field;
        return $field;
    }

    public function string(string $name) : StringField
    {
        return $this->addField(StringField::class, $name);
    }

    public function integer(string $name) : IntegerField
    {
        return $this->addField(IntegerField::class, $name);
    }

    public function float(string $name) : FloatField
    {
        return $this->addField(FloatField::class, $name);
    }

    public function boolean(string $name) : BooleanField
    {
        return $this->addField(BooleanField::class, $name);
    }

    /**
     * @template ClassT
     * @template EnumT of class-string<ClassT>|string[]
     * @param EnumT $enum
     * @return EnumField<(EnumT is string[] ? value-of<EnumT> : ClassT)>
     */
    public function enum(string $name, string|array $enum) : EnumField
    {
        return $this->addField(EnumField::class, $name, $enum);
    }

}