<?php
namespace Registration\Field;

class Total {
    public function render ($field) {
        return number_format($this->document['total'], 2);
    }
}