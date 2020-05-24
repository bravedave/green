<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green;

use Json;
use strings;

class controller extends \Controller {
	protected $label = config::label;

	protected function before() {

		// config::docmgr_checkdatabase();
		config::route_register( 'maintenance', 'green\maintenance');
		config::route_register( 'beds_list', 'green\beds_list\controller');
		parent::before();

		// sys::logger( sprintf('<%s> %s', 'hear me !', __METHOD__));

	}

	protected function posthandler() {
		$action = $this->getPost('action');

		if ( 'gibblegok' == $action) {
			Json::ack( $action);

		}
		else {
			parent::postHandler();

		}

	}

	protected function _index() {
		$this->render(
			[
				'title' => $this->title = $this->label,
				'primary' => 'box',
				'secondary' => [
					'index-title',
					'index-main',

				],
				'data' => (object)[
					'searchFocus' => true,
					'pageUrl' => strings::url( $this->route)

				],

			]

		);

	}

}
