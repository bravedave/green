<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\postcodes\dao;
use dao\_dao;

class postcodes extends _dao {
    protected $_db_name = 'postcodes';
    protected $template = '\green\postcodes\dao\dto\postcodes';

    function count() : int {
        if ( $res = $this->Result( sprintf( 'SELECT COUNT(*) as i FROM `%s`', $this->_db_name))) {
            if ( $dto = $res->dto()) {
                return $dto->i;

            }

        }

        return 0;

    }

    function import() {
        $qs = \file( implode( DIRECTORY_SEPARATOR, [
            __DIR__,
            'postcodes.sql'

        ]));

        foreach( $qs as $q)
            $this->Q( $q);
        // return;

        // // $q = preg_replace( '@^[^\(]*\(@', '(', $q);
        // // $a = \preg_split( '@\([^\)]*\)@', $q);
        // if ( preg_match_all( '@\([^\)]*\)@', $q, $a)) {
        //     foreach ( $a[0] as $sql) {
        //         // printf( 'INSERT INTO `%s`%s', $this->_db_name, $sql);
        //         // print PHP_EOL;
        //         $this->Q( sprintf( 'INSERT INTO `%s` VALUES %s', $this->_db_name, $sql));

        //     }

        // }


    }

}
