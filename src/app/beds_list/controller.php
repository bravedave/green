<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\beds_list;

use Json;
use strings;

class controller extends \Controller {
	protected $label = config::label;

	protected function before() {
		config::green_beds_list_checkdatabase();
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
			\Json::ack( $action)
				->add( 'data', dao\beds_list::beds());

		}
		elseif ( 'delete' == $action) {
			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\beds_list;
				$dao->delete( $id);

				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		elseif ( 'save-beds' == $action) {
			$a = [
				'beds' => $this->getPost('beds'),
				'description' => $this->getPost('description')
			];

			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\beds_list;
				$dao->UpdateByID( $a, $id);
				Json::ack( $action)
					->add( 'id', $id);

			}
			else {
				if ( $a['beds'] && $a['description']) {
					$dao = new dao\beds_list;
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
		$dao = new dao\beds_list;
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
			'title' => $this->title = 'Add Beds',
			'dto' => new dao\dto\beds_list

		];

		if ( $id = (int)$id) {
			$dao = new dao\beds_list;
			if ( $dto = $dao->getByID( $id)) {
				$this->data->title = $this->title = 'Edit Beds';
				$this->data->dto = $dto;
				$this->load('edit-beds');

			}
			else {
				$this->load('beds-not-found');

			}

		}
		else {
			$this->load('edit-beds');

		}

	}

}
