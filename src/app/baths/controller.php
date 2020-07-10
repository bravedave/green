<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\baths;

use Json;
use strings;

class controller extends \Controller {
	protected $label = config::label;

	protected function before() {

		config::green_baths_checkdatabase();
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

		if ( 'add' == $action) {
			$a = [
				'bath' => (string)$this->getPost( 'bath'),
				'description' => (string)$this->getPost( 'description')

			];
			if ( $a['bath'] && $a['description']) {
				$dao = new dao\bath_list;
				$dao->Insert( $a);
				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		elseif ( 'delete' == $action) {
			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\bath_list;
				$dao->delete( $id);

				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		elseif ( 'get' == $action ) {
			Json::ack( $action)
				->add( 'data', dao\bath_list::baths());

		}
		elseif ( 'update' == $action ) {
			if ( $id = (int)$this->getPost( 'id')) {
				$a = [
					'bath' => (string)$this->getPost( 'bath'),
					'description' => (string)$this->getPost( 'description')

				];

				$dao = new dao\bath_list;
				$dao->UpdateByID( $a, $id );
				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		else {
			parent::postHandler();

		}

	}

	protected function _index() {
		$dao = new dao\bath_list;
		$this->data = (object)[
			'dataset' => $dao->getAll(),
			'dtoSet' => dao\bath_list::baths(),

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

				]

			]

		);

	}

}
