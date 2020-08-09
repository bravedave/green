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

  public function getByEmail( $email) {
    \sys::logger( sprintf('<%s> %s', __NAMESPACE__, __METHOD__));

    $sql = sprintf( 'SELECT * FROM `%s` WHERE `email` = "%s"',
      $this->_db_name,
      $this->escape( $email));

    if ( $res = $this->Result( $sql)) {
      return $res->dto( $this->template);

    }

    return false;

  }

}
