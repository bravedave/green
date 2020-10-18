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

class config extends \config {
  const green_people_db_version = 0.02;

  const label = 'People';

  static protected $_GREEN_PEOPLE_VERSION = 0;

	static protected function green_people_version( $set = null) {
		$ret = self::$_GREEN_PEOPLE_VERSION;

		if ( (float)$set) {
			$config = self::green_people_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$_GREEN_PEOPLE_VERSION = $j->green_people_version = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

		}

		return $ret;

	}

	static function green_people_checkdatabase() {
		if ( self::green_people_version() < self::green_people_db_version) {
      $dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

			config::green_people_version( self::green_people_db_version);

		}

		// sys::logger( 'bro!');

	}

	static function green_people_config() {
		return implode( DIRECTORY_SEPARATOR, [
            rtrim( self::dataPath(), '/ '),
            'green_people.json'

        ]);

	}

  static function green_people_init() {
		if ( file_exists( $config = self::green_people_config())) {
			$j = json_decode( file_get_contents( $config));

			if ( isset( $j->green_people_version)) {
				self::$_GREEN_PEOPLE_VERSION = (float)$j->green_people_version;

			};

		}

	}

}

config::green_people_init();
