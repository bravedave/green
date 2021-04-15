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
  const max_results = 10;

  static public $peopleFields = [
    'id',
    'name',
    'email',
    'mobile'

  ];

  static public function people( string $term) : array {
    $db = sys::dbi();
    $results = [];

    if ( $term) {
      $where = [];
      $whereOR = [];
      $terms = explode( ' ', $term);
      foreach ( $terms as $_t) {
        $where[] = sprintf( 'name LIKE "%%%s%%"', $db->escape( $_t));
        if ( \preg_match( '@^[0-9]*$@', $_t)) {
          $whereOR[] = sprintf( 'mobile LIKE "%%%s%%"', $db->escape( $_t));

        }

      }

      $condition = implode( ' AND ', $where);
      if ( $whereOR) {
        $condition = sprintf(
          '(%s) OR (%s)',
          implode( ' AND ', $where),
          implode( ' AND ', $whereOR)

        );

      }

      /**
       * SELECT * FROM people WHERE
       *  name LIKE "%%0418%%"
       * OR
       *  mobile LIKE "0418%%"
       */

      // priotitise our guys
      $orderBy = [];
      if ( config::$EMAILDOMAIN && config::$SUPPORT_EMAIL) {
        $orderBy[] = sprintf( 'CASE
          WHEN `email` LIKE "%%@%s" THEN 0
          WHEN `email` = "%s" THEN 0
          ELSE 1
          END',
          config::$EMAILDOMAIN,
          config::$SUPPORT_EMAIL

        );

      }
      elseif ( config::$EMAILDOMAIN) {
        $orderBy[] = sprintf( 'CASE
          WHEN `email` LIKE "%%@%s" THEN 0
          ELSE 1
          END',
          config::$EMAILDOMAIN

        );

      }
      elseif ( config::$SUPPORT_EMAIL) {
        $orderBy[] = sprintf( 'CASE
          WHEN `email` = "%s" THEN 0
          ELSE 1
          END',
          config::$SUPPORT_EMAIL

        );

      }

      $orderBy[] = sprintf( 'CASE
        WHEN `name` LIKE "%s%%" THEN 0
        WHEN `name` LIKE "%%%s%%" THEN 1
        ELSE 2
        END',
        $db->escape( $term),
        $db->escape( $term)

      );

      $sql = sprintf(
        'SELECT `%s` FROM `people` WHERE %s
        ORDER BY %s
        LIMIT %d',
        implode( '`,`', self::$peopleFields),
        $condition,
        implode( ',', $orderBy),
        self::max_results

      );

      // \sys::logSQL( sprintf('<%s> %s', $sql, __METHOD__));

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

  static public function postcode( string $term) : array {
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

  static public function properties( string $term, string $restriction = '') : array {
    $db = sys::dbi();
    $results = [];

    $ors = [ sprintf( '(p.address_street like "%%%s%%")', $db->escape( $term)) ];

    $a = explode( ' ', $term);
    if ( count( $a) > 1) {
      $where = [];
      foreach( $a as $k ) {
        if ( $k) {
          $where[] = sprintf(
            '(p.address_street like "%s" OR p.address_suburb like "%s")',
            $db->escape( '%' . $k . '%' ),
            $db->escape( '%' . $k . '%' )

          );

        }

      }
      $ors[] = sprintf( '(%s)', implode( ' AND ', $where ));

    }
    else {
      $ors[] = sprintf( '(replace( p.address_street, " ", "") like "%s%%")', $db->escape( $term));

    }

    $where = [ sprintf( '(%s)', implode( ' OR ', $ors))];
    if ( $restriction) $where[] = $restriction;

    $sql = sprintf(
      'SELECT
        p.id,
        p.address_street,
        p.address_suburb,
        p.address_state,
        p.address_postcode,
        `pc`.state
      FROM
        `properties` p
        LEFT JOIN ( SELECT DISTINCT postcode, state from `postcodes`) pc
          ON `pc`.`postcode` = p.`address_postcode`
      WHERE
        %s
      LIMIT %d',
        implode( ' AND ', $where),
        self::max_results

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
          'state' => $dto->address_state ? $dto->address_state : $dto->state,
          'postcode' => $dto->address_postcode,
          'type' => 'properties'

        ];

      }

    }

    return $results;

  }

  static public function term( string $term) : array {
    $properties = self::properties( $term);
    $people = self::people( $term);

    $maxPeople = max(
      (int)(self::max_results / 2),
      self::max_results - count( $properties)

    );


    $r = [];
    $i = 0;
    foreach ($people as $p) {
      $r[] = $p;
      if ( ++$i >= $maxPeople) break;

    }

    foreach ($properties as $p) {
      $r[] = $p;
      if ( ++$i >= self::max_results) break;

    }

    return $r;

  }

}
