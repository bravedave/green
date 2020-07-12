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

use Json;
use strings;

class controller extends \Controller {
	protected $label = config::label;

	protected function before() {
		config::green_properties_checkdatabase();
		parent::before();

	}

	protected function getView( $viewName = 'index', $controller = null, $logMissingView = true) {
		$view = sprintf( '%s/views/%s.php', __DIR__, $viewName );		// php
		if ( file_exists( $view))
			return ( $view);

		return parent::getView( $viewName, $controller, $logMissingView);

	}

	protected function postHandler() {
		$action = $this->getPost( 'action');

		if ( 'delete' == $action) {
			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\properties;
				$dao->delete( $id);

				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		elseif ( 'save-property' == $action) {
			$a = [
				'address_street' => $this->getPost('address_street'),
				'address_suburb' => $this->getPost('address_suburb'),
				'address_postcode' => $this->getPost('address_postcode'),

			];

			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\properties;
				$dao->UpdateByID( $a, $id);
				Json::ack( $action);

			}
			else {
				if ( $a['address_street'] && $a['address_suburb'] && $a['address_postcode']) {

					$dao = new dao\properties;
					$dao->Insert( $a);
					Json::ack( $action);

				} else { Json::nak( $action); }

			}

		}
		else {
			parent::postHandler();

		}

	}

	protected function _index() {
		$dao = new dao\properties;
		$this->data = (object)[
			'dataset' => $dao->getAll()

		];

		$this->render(
			[
				'title' => $this->title = $this->label,
				'primary' => 'report',
				'secondary' => [
					'index-title',
					'index-up',

				],
				'data' => (object)[
					'searchFocus' => true,
					'pageUrl' => strings::url( $this->route)

				],

			]

		);

	}

	function edit( $id = 0) {
		$this->data = (object)[
			'title' => $this->title = 'Add Property',
			'dto' => new dao\dto\properties

		];

		if ( $id = (int)$id) {
			$dao = new dao\properties;
			if ( $dto = $dao->getByID( $id)) {

				$this->data->dto = $dto;
				$this->load('edit-property');

			}
			else {
				$this->load('property-not-found');

			}

		}
		else {
			$this->load('edit-property');

		}

	}

}
