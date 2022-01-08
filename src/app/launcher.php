<?php
/**
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green;

use dvc\application;

class launcher {
	static function startDir() {
		return dirname( __DIR__);

	}

	static function run() {
		new application( self::startDir());

	}

}
