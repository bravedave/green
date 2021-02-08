<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\people\dao\dto;

use dao\dto\_dto;

class people extends _dto {
  public $id = 0;
	public $name = '';
	public $email = '';
	public $mobile = '';
	public $mobile2 = '';
	public $telephone = '';
	public $telephone_business = '';
	public $address_street = '';
	public $address_suburb = '';
	public $address_postcode = '';

}
