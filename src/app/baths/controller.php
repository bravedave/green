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
  
	protected function _index() {
		$dao = new dao\bath_list;
		$this->data = (object)[
			'dataset' => $dao->getAll(),
			'dtoSet' => dao\bath_list::baths(),

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

				]

			]

		);

	}

	protected function before() {
		config::green_baths_checkdatabase();
		$this->viewPath[] = __DIR__ . '/views/';
		parent::before();

	}

	protected function postHandler() {
		$action = $this->getPost( 'action');

		if ( 'get' == $action ) {
			Json::ack( $action)
				->add( 'data', dao\bath_list::baths());

		}
		elseif ( 'create-default-set' == $action) {
			$dao = new dao\bath_list;
			$dao->createDefaults();

			Json::ack( $action);

		}
		elseif ( 'delete' == $action) {
			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\bath_list;
				$dao->delete( $id);

				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		elseif ( 'save-baths' == $action) {
			$a = [
				'bath' => (string)$this->getPost( 'bath'),
				'description' => (string)$this->getPost( 'description')
			];

			if ( $id = (int)$this->getPost( 'id')) {
				$dao = new dao\bath_list;
				$dao->UpdateByID( $a, $id);
				Json::ack( $action)
					->add( 'id', $id);

			}
			else {
				if ( $a['bath'] && $a['description']) {
					$dao = new dao\bath_list;
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
			'title' => $this->title = 'Add Baths',
			'dto' => new dao\dto\bath_list

		];

		if ( $id = (int)$id) {
			$dao = new dao\bath_list;
			if ( $dto = $dao->getByID( $id)) {
				$this->data->title = $this->title = 'Edit Baths';
				$this->data->dto = $dto;
				$this->load('edit-baths');

			}
			else {
				$this->load('baths-not-found');

			}

		}
		else {
			$this->load('edit-baths');

		}

	}

}
