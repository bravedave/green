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

use Json;
use strings;
use sys;

class controller extends \Controller {
  protected $label = config::label;
  protected $viewPath = __DIR__ . '/views/';

	protected function before() {
		config::green_people_checkdatabase();
		parent::before();

	}

	protected function postHandler() {
		$action = $this->getPost( 'action');

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
				'telephone' => $this->getPost('telephone'),
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
		else {
			parent::postHandler();

		}

	}

	protected function _index() {
		$dao = new dao\people;
		$this->data = (object)[
			'dataset' => $dao->getAll()

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

	function edit( $id = 0) {
		$this->data = (object)[
			'title' => $this->title = 'Add People',
			'dto' => new dao\dto\people

		];

		if ( $id = (int)$id) {
			$dao = new dao\people;
			if ( $dto = $dao->getByID( $id)) {

				$this->data->title = $this->title = 'Edit People';
				$this->data->dto = $dto;
				$this->load('edit-people');

			}
			else {
  			$this->title = 'error';
				$this->load('people-not-found');

			}

		}
		else {
			$this->load('edit-people');

		}

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

}
