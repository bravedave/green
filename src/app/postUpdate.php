<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green;

use dvc\service;

class postUpdate extends service {
  protected function _upgrade() {
    config::route_register( 'home', 'green\\controller');
    config::route_register( 'admin', 'green\\admin');
    config::route_register( 'beds_list', 'green\\beds_list\\controller');
    config::route_register( 'baths', 'green\\baths\\controller');
    config::route_register( 'properties', 'green\\properties\\controller');
    config::route_register( 'property_type', 'green\\property_type\\controller');
    config::route_register( 'postcodes', 'green\\postcodes\\controller');
    config::route_register( 'people', 'green\\people\\controller');
    config::route_register( 'users', 'green\\users\\controller');

    property_diary\config::green_property_diary_checkdatabase();

    echo( sprintf('%s : %s%s', 'green updated', __METHOD__, PHP_EOL));

  }

  static function upgrade() {
    $app = new self( launcher::startDir());
    $app->_upgrade();

  }

}
