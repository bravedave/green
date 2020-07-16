<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\users\dao;

use dao\_dao;

class users extends _dao {
	protected $_db_name = 'users';
	protected $template = '\green\users\dao\dto\users';

}