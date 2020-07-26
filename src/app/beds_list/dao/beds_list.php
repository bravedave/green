<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\beds_list\dao;

use dao\_dao;

class beds_list extends _dao {
	protected $_db_name = 'beds_list';
	protected $template = '\green\beds_list\dao\dto\beds_list';

	public function getAll( $fields = '*', $order = 'ORDER BY beds' ) {
		return ( parent::getAll( $fields, $order ));

	}

	static function beds() {
		$dao = new self;
		if ( $res = $dao->getAll()) {
			return $dao->dtoSet( $res);

		}

		return [];

	}

}