<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\users;

class config extends \config {
	const green_users_db_version = 0.01;

    const label = 'Users';

    static protected $_GREEN_USERS_VERSION = 0;

	static protected function green_users_version( $set = null) {
		$ret = self::$_GREEN_USERS_VERSION;

		if ( (float)$set) {
			$config = self::green_users_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$_GREEN_USERS_VERSION = $j->green_users_version = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

		}

		return $ret;

	}

	static function green_users_checkdatabase() {
		if ( self::green_users_version() < self::green_users_db_version) {
      $dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

			config::green_users_version( self::green_users_db_version);

		}

		// sys::logger( 'bro!');

	}

	static function green_users_config() {
		return implode( DIRECTORY_SEPARATOR, [
            rtrim( self::dataPath(), '/ '),
            'green_users.json'

        ]);

	}

    static function green_users_init() {
		if ( file_exists( $config = self::green_users_config())) {
			$j = json_decode( file_get_contents( $config));

			if ( isset( $j->green_users_version)) {
				self::$_GREEN_USERS_VERSION = (float)$j->green_users_version;

			};

		}

	}

}

config::green_users_init();
