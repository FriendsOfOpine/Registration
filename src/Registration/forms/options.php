<?php
namespace Registration\Form;

class options {
	public function __construct ($field) {
		$this->field = $field;
	}
	public $storage = [
		'collection'	=> 'registration_orders',
		'key'			=> '_id'
	];
	public $after = 'notice';
	public $function = 'Your message was saved';

	function quantityField() {
		return [
			'name'		=> 'quantity',
			'display'	=> 'InputSelect',
			'required' 	=> true
		];
	}
}