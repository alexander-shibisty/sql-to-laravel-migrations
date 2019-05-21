<?php namespace SqlParser\Types;

use SqlParser\Types\Traits\{NullableTrait, IndexesTrait, SizeTrait};

class IntegerType extends Type {
    use NullableTrait, SizeTrait, IndexesTrait;
    
    const LENGHT = 10;

    public function __construct($field, $keys = []) {
        $this->field = $field;
        $this->keys = $keys;
    }

    public function __toString() {

        $fieldString = addSpaces(4) . '$table'
            .'->addColumn(\'integer\', \''.$this->field->name.'\', [\'unsigned\' => '.$this->getSign().', \'length\' => '.$this->getSize().', \'autoIncrement\' => '.$this->getAutoincrement().'])'
                .$this->getNullable()
                .$this->getDefaultValue()
                .$this->getPrimary($this->getAutoincrement())
                .$this->getIndex()
                .$this->getUnique()
            .';'."\n";

        // $fieldString = addSpaces(4) . '$table->'
        //     .$this->getSign()
        //     .'(\''.$this->field->name."', null".$this->getSize()
        // .");\n";

        return $fieldString;
    }

    public function getSign(): string {
        if(isset($this->field->type->options->options[4]) && $this->field->type->options->options[4] === 'UNSIGNED') {
            return 'true';
        }

        return 'false';
    }

    public function getAutoincrement(): string {
        if(isset($this->field->options->options[3]) && $this->field->options->options[3] === 'AUTO_INCREMENT') {
            return 'true';
        }

        return 'false';
    }

    public function getDefaultValue(): ?string {
        if(isset($this->field->options->options[2]['name']) && $this->field->options->options[2]['name'] === 'DEFAULT') {
            return '->default('.((int)$this->field->options->options[2]['value']).')';
        }

        return null;
    }

}