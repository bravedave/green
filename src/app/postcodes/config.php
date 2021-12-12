<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\postcodes;

use green\config as GreenConfig;

class config extends GreenConfig {
	const green_postcodes_db_version = 0.03;

  const label = 'Postcodes';

  static function green_postcodes_checkdatabase() {
    $dao = new dao\dbinfo(null, self::dataStore());
    // // $dao->debug = true;
    $dao->checkVersion('green_postcodes', self::green_postcodes_db_version);

    if (file_exists($_file = self::green_postcodes_config())) {
      \sys::logger(sprintf('cleanup %s', $_file));
      unlink($_file);
    }
  }

	static function green_postcodes_config() {
		return implode( DIRECTORY_SEPARATOR, [
      rtrim( self::dataPath(), '/ '),
      'green_postcodes.json'

    ]);

	}

}
