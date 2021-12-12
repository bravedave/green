<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/
namespace green\beds_list;

use green\config as GreenConfig;

class config extends GreenConfig {
	const green_beds_list_db_version = 0.01;

  const label = 'Beds List';

  static function green_beds_list_checkdatabase() {
    $dao = new dao\dbinfo(null, self::dataStore());
    // // $dao->debug = true;
    $dao->checkVersion('green_beds_list', self::green_beds_list_db_version);

    if (file_exists($_file = self::green_beds_list_config())) {
      \sys::logger(sprintf('cleanup %s', $_file));
      unlink( $_file);
    }
  }

	static function green_beds_list_config() {
		return implode( DIRECTORY_SEPARATOR, [
      rtrim( self::dataPath(), '/ '),
      'green_beds_list.json'

    ]);

	}

}
