<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green;

use dvc\service;

class updater extends service {
    protected function _upgrade() {
        config::route_register( 'green', 'green\\controller');
        config::route_register( 'maintenance', 'green\\maintenance');
        config::route_register( 'beds_list', 'green\\beds_list\\controller');
        config::route_register( 'baths', 'green\\baths\\controller');

        echo( sprintf('%s : %s%s', 'green updated', __METHOD__, PHP_EOL));

    }

    static function run() {
        if ( method_exists( '\application', 'rootDir')) {
            $app = new self();
            $app->_upgrade();

        }
        else {
            echo 'cannot register application methods';

        }

    }

}
