<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\users\dao\dto;

use dvc\dao\dto\_dto;

class users extends _dto {
	public $id = 0;

	public $name = '';
	public $admin = false;
	public $active = true;
	public $email = '';
	public $mobile = '';
	public $group = '';
	public $birthdate = '';
	public $start_date = '';

  function __construct( $row = null) {
    $this->start_date = date('Y-m-d');
    parent::__construct( $row);

  }

}
