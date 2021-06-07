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

class config extends \config {
	const green_properties_db_version = 0.03;

  const label = 'Properties';

  static protected $_GREEN_PROPERTIES_VERSION = 0;

	static protected function green_properties_version( $set = null) {
		$ret = self::$_GREEN_PROPERTIES_VERSION;

		if ( (float)$set) {
			$config = self::green_properties_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$_GREEN_PROPERTIES_VERSION = $j->green_properties_version = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
      chmod( $config, 0664);

		}

		return $ret;

	}

	static function green_properties_checkdatabase() {
		if ( self::green_properties_version() < self::green_properties_db_version) {
      $dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

			config::green_properties_version( self::green_properties_db_version);

		}

		// sys::logger( 'bro!');

	}

	static function green_properties_config() {
		return implode( DIRECTORY_SEPARATOR, [
      rtrim( self::dataPath(), '/ '),
      'green_properties.json'

    ]);

	}

  static function green_properties_init() {
		if ( file_exists( $config = self::green_properties_config())) {
			$j = json_decode( file_get_contents( $config));

			if ( isset( $j->green_properties_version)) {
				self::$_GREEN_PROPERTIES_VERSION = (float)$j->green_properties_version;

			};

		}

	}

}

config::green_properties_init();
