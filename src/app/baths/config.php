<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\baths;

use green\config as GreenConfig;

class config extends GreenConfig {
  const green_baths_db_version = 0.01;

  const label = 'Baths';

  static function green_baths_checkdatabase() {
    $dao = new dao\dbinfo(null, self::dataStore());
    // // $dao->debug = true;
    $dao->checkVersion('green_baths', self::green_baths_db_version);

    if (file_exists($_file = self::green_baths_config())) {
      \sys::logger(sprintf('cleanup %s', $_file));
      unlink($_file);
    }
  }

  static function green_baths_config() {
    return implode(DIRECTORY_SEPARATOR, [
      rtrim(self::dataPath(), '/ '),
      'green_baths.json'

    ]);
  }
}
