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

class config extends \config {
	const green_baths_db_version = 0.01;

  const label = 'Baths';

  static protected $_GREEN_BATHS_VERSION = 0;

	static protected function green_baths_version( $set = null) {
		$ret = self::$_GREEN_BATHS_VERSION;

		if ( (float)$set) {
			$config = self::green_baths_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$_GREEN_BATHS_VERSION = $j->green_baths_version = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

		}

		return $ret;

    }

    static function green_baths_checkdatabase() {
		if ( self::green_baths_version() < self::green_baths_db_version) {
      $dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

			config::green_baths_version( self::green_baths_db_version);

		}

		// sys::logger( 'bro!');

    }

	static function green_baths_config() {
		return implode( DIRECTORY_SEPARATOR, [
      rtrim( self::dataPath(), '/ '),
      'green_baths.json'

    ]);

	}

  static function green_baths_init() {
		if ( file_exists( $config = self::green_baths_config())) {
			$j = json_decode( file_get_contents( $config));

			if ( isset( $j->green_baths_version)) {
				self::$_GREEN_BATHS_VERSION = (float)$j->green_baths_version;

			};

		}

  }

}

config::green_baths_init();
