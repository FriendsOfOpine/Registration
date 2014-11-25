<?php
namespace Registration\Form;

class options {
    public function __construct ($field) {
        $this->field = $field;
    }
    public $storage = [
        'collection'    => 'registration_orders',
        'key'           => '_id'
    ];
    public $after = 'redirect';

    function quantityField() {
        return [
            'name'      => 'quantity',
            'display'   => 'Registration\Field\OptionQuantity',
            'required'  => true
        ];
    }

    function eventSlugField () {
        return [
            'name'     => 'event_slug',
            'display'  => 'InputHidden',
            'required' => true
        ];
    }
}