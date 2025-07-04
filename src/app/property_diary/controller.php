<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\property_diary;

use strings;

class controller extends \Controller {
	protected $label = config::label;
  
  protected function _index() {
    $dao = new dao\property_diary;
    $this->data = (object)[
      'pageUrl' => strings::url( $this->route),
      'res' => $dao->getAll(),
      'title' => $this->title = $this->label,

    ];

    $this->render([
      'primary' => 'report',
      'secondary' => [
        'index-title',
        'index-up'

      ],

    ]);

  }

  protected function before() {
    config::green_property_diary_checkdatabase();
    $this->viewPath[] = __DIR__ . '/views/';
    parent::before();
  }
}
