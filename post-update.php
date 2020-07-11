#!/usr/bin/env php
<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

require "vendor/autoload.php";

class updater extends dvc\service {
    protected function _upgrade() {
        // config::route_register( 'green', 'green\\controller');
        // config::route_register( 'maintenance', 'green\\maintenance');
        // config::route_register( 'beds_list', 'green\\beds_list\\controller');
        // config::route_register( 'baths', 'green\\baths\\controller');

        // \sys::logger( sprintf('%s : %s', 'green updated', __METHOD__));
        echo( sprintf('%s : %s%s', 'green updated', __METHOD__, PHP_EOL));

    }

    static function upgrade() {
        $app = new self( __DIR__ . '/src/' );
        $app->_upgrade();

    }

}

updater::upgrade();
