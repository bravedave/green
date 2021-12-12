<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\users;

use green\config as GreenConfig;

class config extends GreenConfig {
  const green_users_db_version = 0.04;

  const label = 'Users';

  static $GREEN_FIELD_ACTIVE = true;
  static $GREEN_FIELD_ADMIN = true;

  static function green_email_enable(): bool {
    return \class_exists('dvc\mail\inbox');
  }

  static function green_users_checkdatabase() {
    $dao = new dao\dbinfo(null, self::dataStore());
    // // $dao->debug = true;
    $dao->checkVersion('green_users', self::green_users_db_version);

    // if (file_exists($_file = self::green_users_config())) {
    //   \sys::logger(sprintf('cleanup %s', $_file));
    //   unlink($_file);
    // }
  }

  static function green_users_config() {
    return implode(DIRECTORY_SEPARATOR, [
      rtrim(self::dataPath(), '/ '),
      'green_users.json'

    ]);
  }

  static function green_users_init() {
    $_a = [
      'field_active' => self::$GREEN_FIELD_ACTIVE,
      'field_admin' => self::$GREEN_FIELD_ADMIN,

    ];

    if (file_exists($config = self::green_users_config())) {
      $j = (object)array_merge($_a, (array)json_decode(file_get_contents($config)));

      self::$GREEN_FIELD_ACTIVE = $j->field_active;
      self::$GREEN_FIELD_ADMIN = $j->field_admin;
    }
  }
}

config::green_users_init();
