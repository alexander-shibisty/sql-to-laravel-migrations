<?php namespace SqlParser\Types;

use SqlParser\Types\Traits\{NullableTrait, IndexesTrait, SizeTrait};

class EnumType extends Type {
    use NullableTrait, SizeTrait, IndexesTrait;
    
    const LENGHT = 10;

    public function __construct($field, $keys = []) {
        $this->field = $field;
        $this->keys = $keys;
    }

    public function __toString() {
        $fieldString = addSpaces(4) . '$table->enum(\''.$this->field->name.'\', ['.implode(', ', $this->field->type->parameters).'])'
            .$this->getNullable()
            .$this->getDefaultValue()
            .$this->getIndex()
            .$this->getUnique()
        .";\n";


        return $fieldString;
    }

    public function getDefaultValue(): ?string {
        if(isset($this->field->options->options[2]['name']) && $this->field->options->options[2]['name'] === 'DEFAULT') {
            return '->default('.((string)$this->field->options->options[2]['value']).')';
        }

        return null;
    }

}