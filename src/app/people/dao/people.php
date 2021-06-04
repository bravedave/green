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
use strings;

class people extends _dao {
	protected $_db_name = 'people';
	protected $template = __NAMESPACE__ . '\dto\people';

  public function getByEmail( $email) {
    $sql = sprintf(
      'sqlite' == \config::$DB_TYPE ?
        'SELECT * FROM `%s` WHERE `email` = \'%s\'' :
        'SELECT * FROM `%s` WHERE `email` = "%s"',
      $this->_db_name,
      $this->escape( $email)

    );

    if ( $res = $this->Result( $sql)) {
      return $res->dto( $this->template);

    }

    return false;

  }

  public function getByPHONE( $tel) {
    if ( $tel = strings::cleanPhoneString( $tel)) {
      $sql = sprintf(
        'sqlite' == \config::$DB_TYPE ?
          'SELECT * FROM `%s` WHERE `mobile` = \'%s\' OR `telephone` = \'%s\' OR `telephone_business` = \'%s\'' :
          'SELECT * FROM `%s` WHERE `mobile` = "%s" OR `telephone` = "%s" OR `telephone_business` = "%s"',
        $this->_db_name,
        $this->escape( $tel),
        $this->escape( $tel),
        $this->escape( $tel)

      );

      if ( $res = $this->Result( $sql)) {
        return $res->dto( $this->template);

      }

    }

    return false;

  }

	public function Insert( $a) {
		$a[ 'created'] = $a['updated'] = self::dbTimeStamp();
		return parent::Insert( $a);

	}

  public function record_count() : int {
    if ( $res = $this->Result( 'SELECT COUNT(`id`) i FROM `people`')) {
      if ( $dto = $res->dto()) {
        return (int)$dto->i;

      }

    }

    return 0;

  }

	public function UpdateByID( $a, $id) {
		$a['updated'] = self::dbTimeStamp();
		return parent::UpdateByID( $a, $id);

  }

}
