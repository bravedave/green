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

use sys;

abstract class search {
    static function term( string $term) : array {
        $db = sys::dbi();
        $results = [];

        $where = [
            sprintf( 'address_street LIKE "%%%s%%"', $db->escape( $term))

        ];

        $sql = sprintf(
            'SELECT
                id,
                address_street
            FROM
                `properties`
            WHERE
                %s',
                implode( ' AND ', $where));
        // sys::logSQL( $sql);
        if ( $res = $db->Result( $sql)) {
            while ( $dto = $res->dto()) {
                $results[] = (object)[
                    'id' => $dto->id,
                    'label' => $dto->address_street,
                    'type' => 'properties'

                ];

            }

        }

        return $results;

    }

}