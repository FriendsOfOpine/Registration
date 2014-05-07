<?php
namespace Registration\Form;

class payments {
	public function __construct ($field) {
		$this->field = $field;
	}
	public $storage = [
		'collection'	=> 'registration_orders',
		'key'			=> '_id'
	];
	public $after = 'redirect';

	function first_nameField () {
        return [
            'name'        => 'first_name',
            'required'    => true,
            'display' => 'InputText'
        ];
    }

    public function last_nameField () {
        return [
            'name'    => 'last_name',
            'required'  => true,
            'display' => 'InputText'
        ];
    }

    public function emailField () {
        return [
            'name'    => 'email',
            'label'   => 'Email',
            'required'  => true,
            'display' => 'InputText'
        ];
    }

    function addressField () {
		return [
			'name' 		=> 'address',
			'label' 	=> 'Address',
			'required' 	=> true,
			'display' 	=> 'InputText'	
		];
	}

	function optionalAddressField () {
		return [
			'name' 		=> 'address2',
			'label' 	=> 'Address2',
			'required' 	=> false,
			'display' 	=> 'InputText'	
		];
	}

	function cityField () {
		return [
			'name' 		=> 'city',
			'label' 	=> 'City',
			'required' 	=> true,
			'display' 	=> 'InputText'	
		];
	}

	function stateField () {
		return [
			'name' 		=> 'state',
			'label' 	=> 'State',
			'required' 	=> true,
			'options' 	=> array(
				'AL'=>"Alabama",
				'AK'=>"Alaska",
				'AS'=>"American Samoa",
				'AZ'=>"Arizona",
				'AR'=>"Arkansas",
				'CA'=>"California",
				'CO'=>"Colorado",
				'CT'=>"Connecticut",
				'DE'=>"Delaware",
				'DC'=>"District Of Columbia",
				'FM'=>"Federated States of Micronesia",
				'FL'=>"Florida",'GA'=>"Georgia",
				'GU'=>"Guam",
				'HI'=>"Hawaii",
				'ID'=>"Idaho",
				'IL'=>"Illinois",
				'IN'=>"Indiana",
		 		'IA'=>"Iowa",
		 		'KS'=>"Kansas",
				'KY'=>"Kentucky",
				'LA'=>"Louisiana",
				'ME'=>"Maine",
				'MH'=>"Marshall Islands",
				'MD'=>"Maryland",
			 	'MA'=>"Massachusetts",
				'MI'=>"Michigan",
				'MN'=>"Minnesota",
				'MS'=>"Mississippi",
				'MO'=>"Missouri",
				'MT'=>"Montana",
				'NE'=>"Nebraska",
				'NV'=>"Nevada",
				'NH'=>"New Hampshire",
				'NJ'=>"New Jersey",
				'NM'=>"New Mexico",
				'NY'=>"New York",
				'NC'=>"North Carolina",
				'ND'=>"North Dakota",
				'MP'=>"Northern Mariana Islands",
				'OH'=>"Ohio",
				'OK'=>"Oklahoma",
				'OR'=>"Oregon",
				'PW'=>"Palau",
				'PA'=>"Pennsylvania",
				'PR'=>"Puerto Rico",
				'RI'=>"Rhode Island",
				'SC'=>"South Carolina",
				'SD'=>"South Dakota",
				'TN'=>"Tennessee",
				'TX'=>"Texas",
				'UT'=>"Utah",
				'VT'=>"Vermont",
				'VI'=>"Virgin Islands",
				'VA'=>"Virginia",
				'WA'=>"Washington",
				'WV'=>"West Virginia",
				'WI'=>"Wisconsin",
				'WY'=>"Wyoming",
				'AA'=>"Armed Forces Americas", 
				'AE'=>"Armed Forces", 
				'AP'=>"Armed Forces Pacific" 
					),
				'display' 	=> 'Select',
				'nullable' 	=> true
			];
		}
	
	function zipcodeField () {
		return [
			'name' 		=> 'zipcode',
			'label' 	=> 'Zipcode',
			'required' 	=> true,
			'display' 	=> 'InputText'	
		];
	}

	function telephoneField () {
		return [
			'name' 		=> 'telephone',
			'label' 	=> 'Phone Number',
			'required' 	=> false,
			'display' 	=> 'InputText'	
		];
	}

	function cardTypeField () {
		return [
			'name' 		=> 'card_type',
			'label' 	=> 'Card Type',
			'required' 	=> true,
			'display' 	=> 'InputText'	
		];
	}

	function cardNumberField () {
		return [
			'name' 		=> 'credit_card',
			'label' 	=> 'Card Number',
			'required' 	=> true,
			'display' 	=> 'InputText'	
		];
	}

	function securityCodeField () {
		return [
			'name' 		=> 'security_code',
			'label' 	=> 'Security Code',
			'required' 	=> true,
			'display' 	=> 'InputText'	
		];
	}

	function expirationDateField() {
		return [
			'name'			=> 'expiration_date',
			'required'		=> true,
			'display'		=> 'InputDatePicker',
			'transformIn'	=> function ($data) {
				return new \MongoDate(strtotime($data));
			},
			'transformOut'	=> function ($data) {
				return date('m/d/Y', $data->sec);
			},
			'default'		=> function () {
				return date('m/d/Y');
			}
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