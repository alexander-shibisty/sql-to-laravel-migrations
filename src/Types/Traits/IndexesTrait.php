<?php namespace SqlParser\Types\Traits;

trait IndexesTrait {
    public function getPrimary(string $hasAutoincrement): ?string {
        if($hasAutoincrement === 'true') return null;

        foreach($this->keys as $key) {
            if(isset($key->key->type) && $key->key->type === 'PRIMARY KEY') {
                return '->primary()';

                break;
            }
        }

        return null;
    }

    public function getIndex(): ?string {
        foreach($this->keys as $key) {
            if(isset($key->key->type) && $key->key->type === 'KEY') {
                return '->index()';

                break;
            }
        }

        return null;
    }

    public function getUnique(): ?string {
        foreach($this->keys as $key) {
            if(isset($key->key->type) && $key->key->type === 'UNIQUE KEY') {
                return '->unique()';

                break;
            }
        }

        return null;
    }
}