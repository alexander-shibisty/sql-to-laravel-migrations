<?php namespace SqlParser\Types;

use SqlParser\Types\Traits\NullableTrait;

class TextType extends Type {
    use NullableTrait;

    public function __construct($field, $keys = []) {
        $this->field = $field;
        $this->keys = $keys;
    }

    public function __toString() {
        return addSpaces(4) . '$table->text(\''.$this->field->name."')"
            .$this->getNullable()
            .$this->getDefaultValue()
        .";\n";
    }

    public function getDefaultValue(): ?string {
        if(isset($this->field->options->options[2]['name']) && $this->field->options->options[2]['name'] === 'DEFAULT') {
            return '->default('.((string)$this->field->options->options[2]['value']).')';
        }

        return null;
    }

}