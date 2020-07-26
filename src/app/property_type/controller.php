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

	protected function before() {
		config::green_property_type_checkdatabase();
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

		if ( $action == 'get') {
			$dao = new dao\property_type;
			\Json::ack( $action)
				->add( 'data', $dao->dtoSet( $dao->getAll()));

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

	protected function _index() {
		$dao = new dao\property_type;
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
