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

class launcher {
	static function startDir() {
		return dirname( __DIR__);

	}

	static function run() {
		new \application( self::startDir());

	}

}
