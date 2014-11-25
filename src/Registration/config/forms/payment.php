<?php
namespace Registration\Form;

class payment {
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
			'display'	=> 'Registration\Field\Summary',
			'required' 	=> false
		];
	}

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

	function address2Field () {
		return [
			'name' 		=> 'address2',
			'label' 	=> 'Address Continued',
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
			'name' 		=> 'phone',
			'label' 	=> 'Phone Number',
			'required' 	=> true,
			'display' 	=> 'InputText'	
		];
	}

	function cardTypeField () {
		return [
			'name' 		=> 'creditcard_type',
			'label' 	=> 'Card Type',
			'required' 	=> true,
			'display' 	=> 'Select',
			'options'   => function () {
				return ['Visa', 'Mastercard', 'Discover', 'American Express'];
			}	
		];
	}

	function cardNumberField () {
		return [
			'name' 		=> 'creditcard_number',
			'label' 	=> 'Card Number',
			'required' 	=> true,
			'display' 	=> 'InputText'	
		];
	}

	function securityCodeField () {
		return [
			'name' 		=> 'creditcard_security_code',
			'label' 	=> 'Security Code',
			'required' 	=> true,
			'display' 	=> 'InputText'	
		];
	}

	function expirationMonthField() {
		return [
	        'name' => 'creditcard_expiration_month',
	        'label' => 'Month',
	        'required' => true,
	        'options' => function () {
	        	$months = range(1, 12);
	        	$monthsOut = [];
	        	foreach ($months as $month) {
	        		$monthsOut[$month] = str_pad($month, 2, "0", STR_PAD_LEFT);
	        	}
	        	return $monthsOut;
	        },
			'display' => 'Select'
		];
	}

	function expirationYearField() {
		return [
	        'name' => 'creditcard_expiration_year',
	        'label' => 'Year',
	        'required' => true,
	        'options' => function () {
	        	return range (date('Y'), (date('Y') + 20));
	        },
			'display' => 'Select'
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