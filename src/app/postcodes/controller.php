<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/


namespace green\postcodes;

use strings;
use Json;

class controller extends \Controller {
  protected $label = config::label;
  protected $viewPath = __DIR__ . '/views/';

	protected function _index() {
		$dao = new dao\postcodes;
		$this->data = (object)[
			'dataset' => $dao->getAll()

        ];

        $secondary = [
            'index-title',
            'index-up',

        ];

        if ( !$dao->count()) $secondary[] = 'index-import';

		$this->render(
			[
				'title' => $this->title = $this->label,
				'primary' => 'report',
				'secondary' => $secondary,
				'data' => (object)[
					'searchFocus' => false,
					'pageUrl' => strings::url( $this->route)

				],

			]

		);

	}

	protected function before() {
		config::green_postcodes_checkdatabase();
		parent::before();

	}

	protected function postHandler() {
		$action = $this->getPost( 'action');

		if ( 'delete' == $action) {
			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\postcodes;
				$dao->delete( $id);

				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		elseif ( 'import-default-set' == $action) {
            set_time_limit( 0);
            $dao = new dao\postcodes;
            $dao->import();

            Json::ack( $action);

		}
		elseif ( 'save-postcodes' == $action) {
			$a = [
				'suburb' => $this->getPost('suburb'),
				'state' => $this->getPost('state'),
				'postcode' => $this->getPost('postcode'),

			];

			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\postcodes;
				$dao->UpdateByID( $a, $id);
				Json::ack( $action)
					->add( 'id', $id);

			}
			else {
				if ( $a['suburb'] && $a['postcode']) {

					$dao = new dao\postcodes;
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

	public function edit( $id = 0) {
		$this->data = (object)[
			'title' => $this->title = 'Add Postcode',
			'dto' => new dao\dto\postcodes

		];

		if ( $id = (int)$id) {
			$dao = new dao\postcodes;
			if ( $dto = $dao->getByID( $id)) {

				$this->data->title = $this->title = 'Edit Postcode';
				$this->data->dto = $dto;
				$this->load('edit-postcodes');

			}
			else {
				$this->load('postcodes-not-found');

			}

		}
		else {
			$this->load('edit-postcodes');

		}

  }

}
