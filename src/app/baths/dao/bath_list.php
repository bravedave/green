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

use dao\_dao;

class bath_list extends _dao {
	protected $_db_name = 'bath_list';
	protected $template = '\green\baths\dao\dto\bath_list';

	public function getAll( $fields = '*', $order = 'ORDER BY bath' ) {
		return ( parent::getAll( $fields, $order ));

	}

	public function getList( $formatForJavaScript = FALSE ) {
		$a = array();
		if ( $res = $this->getAll()) {

			while ($row = $res->fetch())
				$a[] = array( "bath" => $row["bath"], "description" => $row["description"] );

			if ($formatForJavaScript) {
				$aJ = Array();
				foreach( $a as $j )
					$aJ[] = array( 'value' => $j["bath"], 'text' => $j["description"] );

				return ( json_encode( $aJ ));

			}

		}

		return ($a);

	}

	static function forJavaScript() {
		return ( self::asJSON());

	}

	static function asJSON() {
		$dao = new bath_list;
		return ( $dao->getList( TRUE));

	}

	static function baths() {
		$dao = new self;
		if ( $res = $dao->getAll()) {
			return $dao->dtoSet( $res);

		}

		return [];

	}

}
