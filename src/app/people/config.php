<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\people;

use green\config as GreenConfig;

class config extends GreenConfig {
  const green_people_db_version = 0.05;

  const label = 'People';

  static function green_people_checkdatabase() {
    $dao = new dao\dbinfo(null, self::dataStore());
    // // $dao->debug = true;
    $dao->checkVersion('green_people', self::green_people_db_version);

    if (file_exists($_file = self::green_people_config())) {
      \sys::logger(sprintf('cleanup %s', $_file));
      unlink($_file);
    }
  }

  static function green_people_config() {
    return implode(DIRECTORY_SEPARATOR, [
      rtrim(self::dataPath(), '/ '),
      'green_people.json'

    ]);
  }
}
