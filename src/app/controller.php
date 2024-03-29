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

use Json;
use strings;

class controller extends \Controller {
  protected $label = config::label;

  protected function before() {
    // config::docmgr_checkdatabase();
    parent::before();
  }

  protected function posthandler() {
    $action = $this->getPost('action');

    if ('search' == $action) {
      if ($term = $this->getPost('term')) {
        Json::ack($action)
          ->add('term', $term)
          ->add('data', search::term($term));
      } else {
        Json::nak($action);
      }
    } else {
      parent::postHandler();
    }
  }

  protected function _index() {

    // 'index-main',

    $this->render([
      'title' => $this->title = $this->label,
      'primary' => [
        'box',
      ],
      'secondary' => [
        'index-title',
        'admin/index',
        'about',
      ],
      'data' => (object)[
        'searchFocus' => true,
        'pageUrl' => strings::url($this->route)
      ],
    ]);
  }
}
