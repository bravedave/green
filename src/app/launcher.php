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
	static function run() {
		new \application( dirname( __DIR__));

	}

}
