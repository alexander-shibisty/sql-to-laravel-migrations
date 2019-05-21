<?php namespace SqlParser\Types;

use SqlParser\Types\Traits\{NullableTrait, IndexesTrait, SizeTrait};

class DecimalType extends Type {
    use NullableTrait, SizeTrait, IndexesTrait;

    const LENGHT = 60;
    const FRACTION = 8;

    public function __construct($field, $keys = []) {
        $this->field = $field;
        $this->keys = $keys;
    }

    public function __toString() {
        return addSpaces(4) . '$table->decimal(\''.$this->field->name."', ".$this->getSize().", ".$this->getFraction().")"
            .$this->getNullable()
            .$this->getDefaultValue()
            .$this->getIndex()
            .$this->getUnique()
        .";\n";
    }

    public function getFraction() {
        if(isset($this->field->type->parameters[1]) && (int)$this->field->type->parameters[1] > 0) {
            return $this->field->type->parameters[1];
        }

        return self::FRACTION;
    }

    public function getDefaultValue(): ?string {
        if(isset($this->field->options->options[2]['name']) && $this->field->options->options[2]['name'] === 'DEFAULT') {
            return '->default('.((float)$this->field->options->options[2]['value']).')';
        }

        return null;
    }

}