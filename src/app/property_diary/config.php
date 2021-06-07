<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\property_diary;

class config extends \config {
	const green_property_diary_db_version = 0.1;

  const label = 'Property Diary';

  static protected $_GREEN_PROPERTY_DIARY_VERSION = 0;

	static protected function green_property_diary_version( $set = null) {
		$ret = self::$_GREEN_PROPERTY_DIARY_VERSION;

		if ( (float)$set) {
			$config = self::green_property_diary_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$_GREEN_PROPERTY_DIARY_VERSION = $j->green_property_diary_version = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
      chmod($config, 0664);

		}

		return $ret;

	}

	static function green_property_diary_checkdatabase() {
		if ( self::green_property_diary_version() < self::green_property_diary_db_version) {
      $dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

			config::green_property_diary_version( self::green_property_diary_db_version);

		}

		// sys::logger( 'bro!');

	}

	static function green_property_diary_config() {
		return implode( DIRECTORY_SEPARATOR, [
      rtrim( self::dataPath(), '/ '),
      'green_property_diary.json'

    ]);

	}

  static function green_property_diary_init() {
		if ( file_exists( $config = self::green_property_diary_config())) {
			$j = json_decode( file_get_contents( $config));

			if ( isset( $j->green_property_diary_version)) {
				self::$_GREEN_PROPERTY_DIARY_VERSION = (float)$j->green_property_diary_version;

			};

		}

	}

}

config::green_property_diary_init();
