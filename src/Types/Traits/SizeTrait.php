<?php namespace SqlParser\Types\Traits;

trait SizeTrait {
    public function getSize(): ?int {
        if(isset($this->field->type->parameters[0]) && (int)$this->field->type->parameters[0] > 0) {
            return $this->field->type->parameters[0];
        }

        return self::LENGHT;
    }
}