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
  protected $template = __NAMESPACE__ . '\dto\properties';

  public function record_count() : int {
    if ( $res = $this->Result( 'SELECT COUNT(`id`) i FROM `properties`')) {
      if ( $dto = $res->dto()) {
        return (int)$dto->i;

      }

    }

    return 0;

  }

}
