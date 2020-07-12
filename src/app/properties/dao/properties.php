<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\properties\dao;

use dao\_dao;

class properties extends _dao {
	protected $_db_name = 'properties';
	protected $template = '\green\properties\dao\dto\properties';

}