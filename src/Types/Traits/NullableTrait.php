<?php namespace SqlParser\Types\Traits;

trait NullableTrait {
    public function getNullable(): ?string {
        if(isset($this->field->options->options[1]) && $this->field->options->options[1] !== 'NOT NULL') {
            return '->nullable()';
        }

        return null;
    }
}