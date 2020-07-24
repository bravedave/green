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
    static function postcode( string $term) : array {
        $db = sys::dbi();
        $results = [];

        $where = [
            sprintf( 'suburb LIKE "%%%s%%"', $db->escape( $term))

        ];

        $sql = sprintf(
            'SELECT
                `id`,
                `suburb`,
                `state`,
                `postcode`
            FROM
                `postcodes`
            WHERE
                %s
                LIMIT 10',
                implode( ' AND ', $where));
        // sys::logSQL( $sql);
        if ( $res = $db->Result( $sql)) {
            while ( $dto = $res->dto()) {
                $results[] = (object)[
                    'id' => $dto->id,
                    'label' => sprintf( '%s %s %s', $dto->suburb, $dto->state, $dto->postcode),
                    'value' => $dto->suburb,
                    'suburb' => $dto->suburb,
                    'state' => $dto->state,
                    'postcode' => $dto->postcode,
                    'type' => 'postcode'

                ];

            }

        }

        return $results;

    }

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