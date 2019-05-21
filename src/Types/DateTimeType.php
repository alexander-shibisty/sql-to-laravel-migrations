<?php namespace SqlParser\Types;

use SqlParser\Types\Traits\{NullableTrait, SizeTrait, IndexesTrait};

class DateTimeType extends Type {
    use NullableTrait, SizeTrait, IndexesTrait;
    
    const LENGHT = 1;

    public function __construct($field, $keys = []) {
        $this->field = $field;
        $this->keys = $keys;
    }

    public function __toString() {
        return addSpaces(4) . '$table->dateTime(\''.$this->field->name."')"
            .$this->getNullable()
            .$this->getDefaultValue()
            .$this->getIndex()
            .$this->getUnique()
        .";\n";
    }

    public function getDefaultValue(): ?string {
        if(isset($this->field->options->options[2]['name']) && $this->field->options->options[2]['name'] === 'DEFAULT') {
            return '->default('.((string)$this->field->options->options[2]['value']).')';
        }

        return null;
    }

}