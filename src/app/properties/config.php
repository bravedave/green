<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\properties;

use green\config as GreenConfig;

class config extends GreenConfig {
	const green_properties_db_version = 2;

  const label = 'Properties';

  static function green_properties_checkdatabase() {
    $dao = new dao\dbinfo(null, self::dataStore());
    // // $dao->debug = true;
    $dao->checkVersion('green_properties', self::green_properties_db_version);

    if (file_exists($_file = self::green_properties_config())) {
      \sys::logger(sprintf('cleanup %s', $_file));
      unlink($_file);
    }
  }

	static function green_properties_config() {
		return implode( DIRECTORY_SEPARATOR, [
      rtrim( self::dataPath(), '/ '),
      'green_properties.json'

    ]);

	}

}
