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

class config extends \config {
	const green_postcodes_db_version = 0.03;

  const label = 'Postcodes';

  static protected $_GREEN_POSTCODES_VERSION = 0;

	static protected function green_postcodes_version( $set = null) {
		$ret = self::$_GREEN_POSTCODES_VERSION;

		if ( (float)$set) {
			$config = self::green_postcodes_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$_GREEN_POSTCODES_VERSION = $j->green_postcodes_version = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

		}

		return $ret;

	}

	static function green_postcodes_checkdatabase() {
		if ( self::green_postcodes_version() < self::green_postcodes_db_version) {
      $dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

			config::green_postcodes_version( self::green_postcodes_db_version);

		}

		// sys::logger( 'bro!');

	}

	static function green_postcodes_config() {
		return implode( DIRECTORY_SEPARATOR, [
      rtrim( self::dataPath(), '/ '),
      'green_postcodes.json'

    ]);

	}

  static function green_postcodes_init() {
		if ( file_exists( $config = self::green_postcodes_config())) {
			$j = json_decode( file_get_contents( $config));

			if ( isset( $j->green_postcodes_version)) {
				self::$_GREEN_POSTCODES_VERSION = (float)$j->green_postcodes_version;

			};

		}

	}

}

config::green_postcodes_init();
