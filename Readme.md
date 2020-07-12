## Support Utiltiy for other applications
* Beds - creates and maintains a list of beds suitable for describing houses (1 bed, 2 bed etc.)
* Baths - creates and maintains a list of bathrooms suitable for describing houses (1 bathroom, 2 bath.. etc.)

## Installation
```bash
composer require bravedave/green
```

## Use
use the composer scripts option to maintin a script which runs on upgrade
```json
	},
	"scripts": {
		"post-update-cmd": [
			"updater::run"
        ]
	}
```

and updater would be in your root directory and look like (for example):
```php
<?php

use dvc\service;

class updater extends service {
    protected function _upgrade() {
        config::route_register( 'beds_list', 'green\\beds_list\\controller');
        config::route_register( 'baths', 'green\\baths\\controller');

        echo( sprintf('%s : %s%s', 'updated..', __METHOD__, PHP_EOL));

    }

    static function run() {
        $app = new self( application::startDir());
        $app->_upgrade();

    }

}
```
