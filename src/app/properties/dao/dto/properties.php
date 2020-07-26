<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\properties\dao\dto;

use dao\dto\_dto;

class properties extends _dto {
    public $id = 0;
	public $address_street = '';
	public $address_suburb = '';
	public $address_postcode = '';
	public $description_beds = '';
	public $description_bath = '';
	public $description_car = '';
	public $description_type = '';

}
