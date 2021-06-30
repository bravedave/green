<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\properties;

use green\search;
use Json;
use strings;

class controller extends \Controller {
  protected $label = config::label;
  protected $viewPath = __DIR__ . '/views/';

  protected function before() {
    config::green_properties_checkdatabase();
    parent::before();
  }

  protected function postHandler() {
    $action = $this->getPost('action');

    if ('delete' == $action) {
      if (($id = (int)$this->getPost('id')) > 0) {
        $dao = new dao\properties;
        $dao->delete($id);

        Json::ack($action);
      } else {
        Json::nak($action);
      }
    } elseif ('save-property' == $action) {
      $a = [
        'updated' => \db::dbTimeStamp(),
        'address_street' => $this->getPost('address_street'),
        'address_suburb' => $this->getPost('address_suburb'),
        'address_state' => $this->getPost('address_state'),
        'address_postcode' => $this->getPost('address_postcode'),
        'description_type' => $this->getPost('description_type'),
        'description_beds' => $this->getPost('description_beds'),
        'description_bath' => $this->getPost('description_bath'),
        'description_car' => $this->getPost('description_car'),
        'people_id' => $this->getPost('people_id'),

      ];

      if (($id = (int)$this->getPost('id')) > 0) {
        $dao = new dao\properties;
        $dao->UpdateByID($a, $id);
        Json::ack($action)
          ->add('id', $id);
      } else {
        if ($a['address_street'] && $a['address_suburb'] && $a['address_postcode']) {

          $dao = new dao\properties;
          $a['created'] = $a['updated'];
          $id = $dao->Insert($a);
          Json::ack($action)
            ->add('id', $id);
        } else {
          Json::nak($action);
        }
      }
    } elseif ('search' == $action) {
      if ($term = $this->getPost('term')) {
        Json::ack($action)
          ->add('term', $term)
          ->add('data', search::properties($term));
      } else {
        Json::nak($action);
      }
    } elseif ('search-person' == $action) {
      if ($term = $this->getPost('term')) {
        // \sys::logger( sprintf('<%s> %s', $term, __METHOD__));

        Json::ack($action)
          ->add('term', $term)
          ->add('data', search::people($term));
      } else {
        Json::nak($action);
      }
    } elseif ('search-postcode' == $action) {
      if ($term = $this->getPost('term')) {
        Json::ack($action)
          ->add('term', $term)
          ->add('data', search::postcode($term));
      } else {
        Json::nak($action);
      }
    } else {
      parent::postHandler();
    }
  }

  protected function _index() {
    $offset = (int)$this->getParam('page');
    if ($offset) $offset--;
    $pageSize = 100;

    $dao = new dao\properties;
    $pages = round(($dao->record_count() / $pageSize) + .5, 0, PHP_ROUND_HALF_UP);
    $this->data = (object)[
      'dataset' => $dao->getAll('*', sprintf('ORDER BY `id` LIMIT %s OFFSET %s', $pageSize, ($offset * $pageSize))),
      'offset' => $offset,
      'pages' => $pages,
      'pagesize' => $pageSize,
      'pageUrl' => strings::url($this->route)

    ];

    // \sys::logger( sprintf('<LIMIT %s OFFSET %s> %s', $pageSize, ($offset * $pageSize), __METHOD__));

    $this->render(
      [
        'title' => $this->title = $this->label,
        'primary' => 'report',
        'secondary' => [
          'index-title',
          'index-up',

        ],
        'data' => (object)[
          'searchFocus' => false,
          'pageUrl' => $this->data->pageUrl

        ],

      ]

    );
  }

  function edit($id = 0) {
    $this->data = (object)[
      'title' => $this->title = 'Add Property',
      'dto' => new dao\dto\properties,
      'contact' => false

    ];

    if ($id = (int)$id) {
      $dao = new dao\properties;
      if ($dto = $dao->getByID($id)) {

        $this->data->title = $this->title = 'Edit Property';
        $this->data->dto = $dto;
        if ($dto->people_id) {
          $dao = new \green\people\dao\people;
          $this->data->contact = $dao->getByID($dto->people_id);
        }

        $this->load('edit-property');
      } else {
        $this->load('property-not-found');
      }
    } else {
      $this->load('edit-property');
    }
  }

  public function view($id = 0) {
    $this->data = (object)[
      'dto' => new dao\dto\properties,
      'title' => $this->title = 'Properties',
      'pageUrl' => strings::url($this->route . '/view/' . $id)


    ];

    if ($id = (int)$id) {
      $dao = new dao\properties;
      if ($dto = $dao->getByID($id)) {

        $this->data->dto = $dto;
        $this->render([
          'data' => [
            'title' => $this->title = $dto->address_street,
            'pageUrl' => $this->data->pageUrl

          ],
          'primary' => 'view-property',
          'secondary' => [
            'index-title',
            'index-list',
            'index-up',

          ]

        ]);
      } else {
        $this->render(['primary' => 'property-not-found']);
      }
    } else {
      $this->render(['primary' => 'property-not-found']);
    }
  }
}
