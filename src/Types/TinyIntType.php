<?php namespace SqlParser\Types;

use SqlParser\Types\Traits\{NullableTrait, IndexesTrait, SizeTrait};

class TinyIntType extends Type {
    use NullableTrait, IndexesTrait, SizeTrait;

    const LENGHT = 4;

    public function __construct($field, $keys = []) {
        $this->field = $field;
        $this->keys = $keys;
    }

    public function __toString() {
        return addSpaces(4) . '$table->tinyInteger(\''.$this->field->name."')"
            .$this->getNullable()
            .$this->getDefaultValue()
            .$this->getIndex()
            .$this->getUnique()
        .";\n";
    }

    public function getDefaultValue(): ?string {
        if(isset($this->field->options->options[2]['name']) && $this->field->options->options[2]['name'] === 'DEFAULT') {
            return '->default('.((int)$this->field->options->options[2]['value']).')';
        }

        return null;
    }

}