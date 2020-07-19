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
        config::route_register( 'home', 'green\\controller');
        config::route_register( 'admin', 'green\\admin');
        config::route_register( 'beds_list', 'green\\beds_list\\controller');
        config::route_register( 'baths', 'green\\baths\\controller');
        config::route_register( 'properties', 'green\\properties\\controller');
        config::route_register( 'people', 'green\\people\\controller');
        config::route_register( 'users', 'green\\users\\controller');

        echo( sprintf('%s : %s%s', 'green updated', __METHOD__, PHP_EOL));

    }

    static function upgrade() {
        $app = new self( launcher::startDir());
        $app->_upgrade();

    }

}
