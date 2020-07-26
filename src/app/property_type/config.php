<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\property_type;

class config extends \config {
	const green_property_type_db_version = 0.01;

    const label = 'Property Type';

    static protected $_GREEN_PROPERTY_TYPE_VERSION = 0;

	static protected function green_property_type_version( $set = null) {
		$ret = self::$_GREEN_PROPERTY_TYPE_VERSION;

		if ( (float)$set) {
			$config = self::green_property_type_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$_GREEN_PROPERTY_TYPE_VERSION = $j->green_property_type_version = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

		}

		return $ret;

	}

	static function green_property_type_checkdatabase() {
		if ( self::green_property_type_version() < self::green_property_type_db_version) {
			config::green_property_type_version( self::green_property_type_db_version);

			$dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

		}

		// sys::logger( 'bro!');

	}

	static function green_property_type_config() {
		return implode( DIRECTORY_SEPARATOR, [
            rtrim( self::dataPath(), '/ '),
            'green_property_type.json'

        ]);

	}

    static function green_property_type_init() {
		if ( file_exists( $config = self::green_property_type_config())) {
			$j = json_decode( file_get_contents( $config));

			if ( isset( $j->green_property_type_version)) {
				self::$_GREEN_PROPERTY_TYPE_VERSION = (float)$j->green_property_type_version;

			};

		}

	}

}

config::green_property_type_init();
