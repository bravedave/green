<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\people;

use green\search;
use Json;
use strings;
use sys;

class controller extends \Controller {

	protected $label = config::label;
  

	protected function before() {

	  config::green_people_checkdatabase();
	  $this->viewPath[] = __DIR__ . '/views/';
	  parent::before();
	}

	protected function postHandler() {
		$action = $this->getPost( 'action');

    // \sys::logger( sprintf('<%s> %s', $action, __METHOD__));

		if ( 'delete' == $action) {
			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\people;
				$dao->delete( $id);

				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		elseif ( 'save-people' == $action) {
			$a = [
				'updated' => \db::dbTimeStamp(),
				'name' => $this->getPost('name'),
				'mobile' => $this->getPost('mobile'),
				'mobile2' => $this->getPost('mobile2'),
				'telephone' => $this->getPost('telephone'),
				'telephone_business' => $this->getPost('telephone_business'),
				'email' => $this->getPost('email'),
				'salute' => $this->getPost('salute'),
				'address_street' => $this->getPost('address_street'),
				'address_suburb' => $this->getPost('address_suburb'),
				'address_suburb' => $this->getPost('address_suburb'),
				'address_postcode' => $this->getPost('address_postcode'),
				'postal_address' => $this->getPost('postal_address'),
				'postal_suburb' => $this->getPost('postal_suburb'),
				'postal_postcode' => $this->getPost('postal_postcode'),

			];

			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\people;
				$dao->UpdateByID( $a, $id);
				Json::ack( $action)
					->add( 'id', $id);

			}
			else {
				if ( $a['name']) {

					$dao = new dao\people;
					$a['created'] = $a['updated'];
					$id = $dao->Insert( $a);
					Json::ack( $action)
						->add( 'id', $id);

				} else { Json::nak( $action); }

			}

		}
    elseif ( 'search' == $action) {
      if ($term = $this->getPost('term')) {
        Json::ack($action)
          ->add('term', $term)
          ->add('data', search::people($term));
      } else {
        Json::nak($action);
      }
    }
    elseif ( 'search-properties' == $action) {
			if ( $term = $this->getPost('term')) {
				Json::ack( $action)
					->add( 'term', $term)
					->add( 'data', search::properties( $term));

			} else { Json::nak( $action); }

    }
		else {
			parent::postHandler();

		}

	}

	protected function _index() {
    $offset = (int)$this->getParam( 'page');
    if ( $offset) $offset--;
    $pageSize = 100;

		$dao = new dao\people;
    $pages = round( ( $dao->record_count() / $pageSize) + .5, 0, PHP_ROUND_HALF_UP);
		$this->data = (object)[
      'dataset' => $dao->getAll( '*', sprintf( 'ORDER BY `id` LIMIT %s OFFSET %s', $pageSize, ($offset * $pageSize))),
			'offset' => $offset,
      'pages' => $pages,
      'pagesize' => $pageSize,
      'pageUrl' => strings::url( $this->route)

    ];

    $secondary = [
      'index-title',
      'index-up',

    ];

    if ( $this->Request->ServerIsLocal()) {
      $secondary[] = 'index-tests';

    }

		$this->render(
			[
				'title' => $this->title = $this->label,
				'primary' => 'report',
				'secondary' => $secondary,
				'data' => (object)[
					'searchFocus' => true,
					'pageUrl' => strings::url( $this->route)

				],

			]

		);

	}

	public function edit( $id = 0) {
		$this->data = (object)[
			'title' => $this->title = 'Add People',
			'dto' => new dao\dto\people

		];

		if ( $id = (int)$id) {
			$dao = new dao\people;
			if ( $dto = $dao->getByID( $id)) {

				$this->data->title = $this->title = 'Edit ' . $dto->name;
				$this->data->dto = $dto;
				$this->load('edit');

			}
			else {
        $this->title = 'error';
        $this->load('people-not-found');

			}

		}
		else {
			$this->load('edit');

		}

  }

  public function getPerson() {
    $this->data = (object)[
      'title' => $this->title = 'Get Person'

    ];

    $this->load( 'get-person');

  }

  public function tests( $test = '') {
    if ( 'find' == $test) {
      if (
        $dto = dao\QuickPerson::find([
          'name' => 'John Citizen',
          'email' => 'john@citizens.tld'
          ])

        ) {

        sys::dump( $dto, null, false);
        printf( '<p><a href="%s">continue</a></p>', strings::url( $this->route));

      }
      else {
        print 'not found :(';

      }

    }
    elseif ( 'harvest' == $test) {
      if (
        $dto = dao\QuickPerson::find([
          'name' => 'John Citizen',
          'email' => 'john@citizens.tld'
          ])

        ) {

        $_dto = dao\QuickPerson::harvest([
          'id' => $dto->id,
          'mobile' => '0418 767676'

        ]);

        sys::dump( $_dto, null, false);
        printf( '<p><a href="%s">continue</a></p>', strings::url( $this->route));

      }

    }

  }

  public function view($id = 0) {
    $this->data = (object)[
      'id' => $id

    ];

    $this->render([
      'title' => $this->title = 'People View',
      'content' => 'view'

    ]);
  }
}
