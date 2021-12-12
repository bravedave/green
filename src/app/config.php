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

class config extends \config {
  const label = 'Green';
  const label_admin = 'Admin';

  static function dataStore() {
    return method_exists(__CLASS__, 'cmsStore') ? self::cmsStore() : self::dataPath();
  }
}
