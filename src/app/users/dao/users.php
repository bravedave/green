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
use dao\_dao;

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
    ) {

		$_sql = sprintf(
      'SELECT
        %s
      FROM
        users
      WHERE
        active > 0
        AND name != "" %s',
        $fields, $order );

		return $this->Result( $_sql);

	}

	public function getTeams() : array {
		$a = [];
		$sql = 'SELECT DISTINCT `group` FROM `users` WHERE `group` <> "" ORDER BY `group`';
		if ( $res = $this->Result( $sql)) {
			while ( $dto = $res->dto()) $a[] = $dto->group;

		}

		return $a;

	}

}
