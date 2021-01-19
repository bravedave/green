<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\postcodes\dao;
use dao\_dao;

class postcodes extends _dao {
  protected $_db_name = 'postcodes';
  protected $template = __NAMESPACE__ . '\dto\postcodes';

  function import() {
    $qs = \file( implode( DIRECTORY_SEPARATOR, [
        __DIR__,
        'postcodes.sql'

    ]));

    foreach( $qs as $q) $this->Q( $q);

  }

}
