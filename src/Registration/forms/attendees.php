<?php
namespace Registration\Form;

class attendees {
	public function __construct ($field) {
		$this->field = $field;
	}
	public $storage = [
		'collection'	=> 'registration_orders',
		'key'			=> '_id'
	];
	public $after = 'redirect';

	function nameField() {
		return [
			'name'		=> 'name',
			'display'	=> 'Registration\Field\Attendees',
			'required' 	=> true
		];
	}

	function totalField() {
		return [
			'name'		=> 'total',
			'display'	=> 'Registration\Field\Total',
			'required' 	=> true
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