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
	protected $_db_cache_prefix = 'green';
	protected $template = __NAMESPACE__ . '\dto\properties';

}
