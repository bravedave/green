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
	protected $_db_cache_prefix = 'green';
	protected $template = __NAMESPACE__ . '\dto\people';

  public function getByEmail( $email) {
    $sql = sprintf( 'SELECT * FROM `%s` WHERE `email` = "%s"',
      $this->_db_name,
      $this->escape( $email));

    if ( $res = $this->Result( $sql)) {
      return $res->dto( $this->template);

    }

    return false;

  }

  public function getByPHONE( $tel) {
    if ( $tel) {
      $sql = sprintf(
        'SELECT * FROM `%s` WHERE `mobile` = "%s" OR `telephone` = "%s"',
        $this->_db_name,
        $this->escape( $tel),
        $this->escape( $tel)

      );

      if ( $res = $this->Result( $sql)) {
        return $res->dto( $this->template);

      }

    }

    return false;

  }

}
