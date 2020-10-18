<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\property_type;

use Json;
use strings;

class controller extends \Controller {
	protected $label = config::label;
  protected $viewPath = __DIR__ . '/views/';

	protected function _index() {
		$dao = new dao\property_type;
		$this->data = (object)[
			'dataset' => $dao->getAll()

		];

		$secondary = [
			'index-title',
			'index-up'

		];

        if ( !$dao->count()) $secondary[] = 'index-defaults';

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

	protected function before() {
		config::green_property_type_checkdatabase();
		parent::before();

	}

	protected function postHandler() {
		$action = $this->getPost( 'action');

		if ( $action == 'get') {
			$dao = new dao\property_type;
			\Json::ack( $action)
				->add( 'data', $dao->dtoSet( $dao->getAll()));

		}
		elseif ( 'create-default-set' == $action) {
			$dao = new dao\property_type;
			$dao->createDefaults();

			Json::ack( $action);

		}
		elseif ( 'delete' == $action) {
			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\property_type;
				$dao->delete( $id);

				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		elseif ( 'save-property-type' == $action) {
			$a = [
				'property_type' => $this->getPost('property_type')
			];

			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\property_type;
				$dao->UpdateByID( $a, $id);
				Json::ack( $action)
					->add( 'id', $id);

			}
			else {
				if ( $a['property_type']) {
					$dao = new dao\property_type;
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
			'title' => $this->title = 'Add Property Type',
			'dto' => new dao\dto\property_type

		];

		if ( $id = (int)$id) {
			$dao = new dao\property_type;
			if ( $dto = $dao->getByID( $id)) {
				$this->data->title = $this->title = 'Edit Property Type';
				$this->data->dto = $dto;
				$this->load('edit-property_type');
				// $this->load('property_type-not-found');

			}
			else {

			}

		}
		else {
			$this->load('edit-property_type');

		}

	}

}
