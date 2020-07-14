<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\people\dao;

use dao\_dao;

class people extends _dao {
	protected $_db_name = 'people';
	protected $template = '\green\people\dao\dto\people';

}