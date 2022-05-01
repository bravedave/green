<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\baths\dao;

use dvc\dao\_dao;

class bath_list extends _dao {
	protected $_db_name = 'bath_list';
	protected $template = '\green\baths\dao\dto\bath_list';

	public function getAll( $fields = '*', $order = 'ORDER BY bath' ) {
		return ( parent::getAll( $fields, $order ));

	}

	static function baths() {
		$dao = new self;
		if ( $res = $dao->getAll()) {
			return $dao->dtoSet( $res);

		}

		return [];

	}

	public function createDefaults() {
		$this->Insert(['bath' => '1', 'description' => '1']);
		$this->Insert(['bath' => '2', 'description' => '2']);
		$this->Insert(['bath' => '3', 'description' => '3']);

	}

}
