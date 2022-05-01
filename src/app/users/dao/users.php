<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\users\dao;

use dvc\bCrypt;
use dvc\mail\credentials;
use green\users\config;
use dvc\dao\_dao;
use strings;

class users extends _dao {
	protected $_db_name = 'users';
	protected $template = __NAMESPACE__ . '\dto\users';

  public function credentials( dto\users $dto) : ?credentials {
    if ( config::green_email_enable()) {
      if ( $dto->mail_account && $dto->mail_password && $dto->mail_server) {
        $creds = new credentials(
          $dto->mail_account,
          bCrypt::decrypt( $dto->mail_password),
          $dto->mail_server

        );

        return $creds;

      }

    }

    return null;

  }

	public function getActive(
    $fields = 'id, name, email, mobile',
    $order = 'ORDER BY name'
    ) : array {

		$_sql = sprintf(
      'SELECT
        %s
      FROM
        users
      WHERE
        active > 0
        AND name != "" %s',
        $fields, $order );

		if ( $res = $this->Result( $_sql)) {
      return $res->dtoSet();

    }

    return [];

	}

	public function getTeams() : array {
		$a = [];
		$sql = 'SELECT DISTINCT `group` FROM `users` WHERE `group` <> "" ORDER BY `group`';
		if ( $res = $this->Result( $sql)) {
			while ( $dto = $res->dto()) $a[] = $dto->group;

		}

		return $a;

	}

	public function getUserByEmail( $email ) {
		if ( strings::IsEmailAddress( $email )) {

      $sql = sprintf(
        'SELECT * FROM `users` WHERE `email` = "%s"',
        $this->escape( $email )

      );

			if ( $res = $this->Result( $sql)) {
        if ( $dto = $res->dto( $this->template)) {
          return $dto;

        }

			}

		}

		return ( false );

	}

}
