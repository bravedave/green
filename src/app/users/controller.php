<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\users;

use Json;
use strings;

class controller extends \Controller {
	protected $label = config::label;

	protected function before() {
		config::green_users_checkdatabase();
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
				$dao = new dao\users;
				$dao->delete( $id);

				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		elseif ( 'save-user' == $action) {
			$a = [
				'updated' => \db::dbTimeStamp(),
				'name' => $this->getPost('name'),
				'admin' => $this->getPost('admin'),
				'active' => $this->getPost('active'),
				'email' => $this->getPost('email'),
				'mobile' => preg_replace( '@[^0-9]@', '', $this->getPost('mobile')),

			];

			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\users;
				$dao->UpdateByID( $a, $id);
				Json::ack( $action)
					->add( 'id', $id);

			}
			else {
				if ( $a['name']) {
					$dao = new dao\users;
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
		$dao = new dao\users;
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
			'title' => $this->title = 'Add Users',
			'dto' => new dao\dto\users

		];

		if ( $id = (int)$id) {
			$dao = new dao\users;
			if ( $dto = $dao->getByID( $id)) {

				$this->data->title = $this->title = 'Edit Users';
				$this->data->dto = $dto;
				$this->load('edit-users');

			}
			else {
				$this->load('users-not-found');

			}

		}
		else {
			$this->load('edit-users');

		}

	}

}
