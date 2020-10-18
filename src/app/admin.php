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

class admin extends \Controller {
	protected $label = config::label_admin;

	protected function _index() {
		$this->render(
			[
				'title' => $this->title = $this->label,
				'primary' => 'box',
				'secondary' => [
					'index-title',
					'admin/index',

				],
				'data' => (object)[
					'searchFocus' => true,
					'pageUrl' => strings::url( $this->route)

				],

			]

		);

	}

}
