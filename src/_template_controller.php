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

class _template extends \Controller {
  protected $viewPath = __DIR__ . '/views/';

  protected function access_control() {
		if ( currentUser::restriction('open-user') == 'yes') {
			return ( true);

		}

		return ( false);

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
				'title' => $this->title = sprintf( '%s : Index', $this->label),
				'primary' => 'blank',
				'secondary' => 'blank',
				'data' => (object)[
					'searchFocus' => true,
					'pageUrl' => strings::url( $this->route)

				],

			]

		);

	}

}
