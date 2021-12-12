<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\property_type;

use green\config as GreenConfig;

class config extends GreenConfig {
  const green_property_type_db_version = 0.01;

  const label = 'Property Type';

  static function green_property_type_checkdatabase() {
    $dao = new dao\dbinfo(null, self::dataStore());
    // // $dao->debug = true;
    $dao->checkVersion('green_property_type', self::green_property_type_db_version);

    if (file_exists($_file = self::green_property_type_config())) {
      \sys::logger(sprintf('cleanup %s', $_file));
      unlink($_file);
    }
  }

  static function green_property_type_config() {
    return implode(DIRECTORY_SEPARATOR, [
      rtrim(self::dataPath(), '/ '),
      'green_property_type.json'

    ]);
  }
}
