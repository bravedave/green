<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\properties\dao;

use dao\_dao;

class properties extends _dao {
  protected $_db_name = 'properties';
  protected $template = __NAMESPACE__ . '\dto\properties';

  public function option(dto\properties $dto, $key = null, $val = null): mixed {

    if ('string' == gettype($dto->options)) {
      $dto->options = (array)json_decode($dto->options);
    }

    $ret = '';
    if (!is_null($dto->options)) {
      if (is_array($dto->options)) {
        if ($key) {
          if (isset($dto->options[$key])) {
            $ret = (string)$dto->options[$key];
          }
        } else {
          $ret = (array)$dto->options;
        }
      }
    }

    if (!is_null($val)) {
      if (is_null($dto->options))
        $dto->options = [];

      /* writer */
      if ((string)$val == '') {
        if (isset($dto->options[$key])) {
          unset($dto->options[$key]);
        }
      } else {
        $dto->options[$key] = (string)$val;
      }

      $a = ['options' => json_encode($dto->options)];

      $this->UpdateByID($a, (int)$dto->id);
    }

    return $ret;
  }

  public function record_count(): int {
    if ($res = $this->Result('SELECT COUNT(`id`) i FROM `properties`')) {
      if ($dto = $res->dto()) {
        return (int)$dto->i;
      }
    }

    return 0;
  }
}
