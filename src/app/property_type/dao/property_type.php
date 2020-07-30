<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\property_type\dao;

use dao\_dao;

class property_type extends _dao {
	protected $_db_name = 'property_type';
	protected $template = '\green\property_type\dao\dto\property_type';

	public function createDefaults() {
		$this->Insert(['property_type' => 'House']);
		$this->Insert(['property_type' => 'Unit']);

	}

}