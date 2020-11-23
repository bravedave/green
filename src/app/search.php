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
  public $peopleFields = [
    'id',
    'name',
    'email',
    'mobile'

  ];

  static function people( string $term) : array {
    $db = sys::dbi();
    $results = [];

    if ( $term) {
      $where = [];
      $terms = explode( ' ', $term);
      foreach ( $terms as $_t) {
          $where[] = sprintf( 'name LIKE "%%%s%%"', $db->escape( $_t));

      }

      $sql = sprintf(
        'SELECT `%s` FROM `people` WHERE %s
        ORDER BY CASE
          WHEN `name` LIKE "%s%%" THEN 0
          WHEN `name` LIKE "%%%s%%" THEN 1
          ELSE 2
          END
        LIMIT 10',
        implode( '`,`', self::$peopleFields),
        implode( ' AND ', $where),
        $db->escape( $term),
        $db->escape( $term)

      );

      if ( $res = $db->Result( $sql)) {
        while ( $dto = $res->dto()) {

          $dto->label = $dto->name;
          $dto->type = 'people';

          $results[] = $dto;

        }

      }

      return $results;

    }

  }

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

  static function properties( string $term, string $restriction = '') : array {
    $db = sys::dbi();
    $results = [];

    $ors = [ sprintf( '(address_street like "%%%s%%")', $db->escape( $term)) ];

    $a = explode( ' ', $term);
    if ( count( $a) > 1) {
        $where = [];
        foreach( $a as $k ) {
            $where[] = sprintf( 'address_street like "%s"', $db->escape( '%' . $k . '%' ));

        }
        $ors[] = sprintf( '(%s)', implode( ' AND ', $where ));

    }
    else {
        $ors[] = sprintf( '(replace( address_street, " ", "") like "%s%%")', $db->escape( $term));

    }

    $where = [ sprintf( '(%s)', implode( ' OR ', $ors))];
    if ( $restriction) $where[] = $restriction;

    $sql = sprintf(
      'SELECT
        id,
        address_street,
        address_suburb,
        address_state,
        address_postcode
      FROM
        `properties`
      WHERE
        %s',
        implode( ' AND ', $where)

    );
    // sys::logSQL( $sql);

    if ( $res = $db->Result( $sql)) {
      while ( $dto = $res->dto()) {
        $results[] = (object)[
          'id' => $dto->id,
          'label' => $dto->address_street,
          'id' => $dto->id,
          'street' => $dto->address_street,
          'suburb' => $dto->address_suburb,
          'state' => $dto->address_state,
          'postcode' => $dto->address_postcode,
          'type' => 'properties'

        ];

      }

    }

    return $results;

  }

  static function term( string $term) : array {
      return self::properties( $term);

  }

}
