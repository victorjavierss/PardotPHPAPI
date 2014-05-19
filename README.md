# PardotPHPAPI

PardotPHPAPI is a php class that makes easier the integration of Pardot® in your website.
It is a wrapper for common functions such as read, create and update.

## Requirements
PHP 5.3

## Usage
### Complete example
```php
<?php
include_once 'lib/Pardot/PardotAPI.php';
include_once 'lib/Pardot/LoginAPI.php';
include_once 'lib/Pardot/ProspectPardot.php';

use \PardotAPI\PardotAPI;

$prospect = new PardotAPI('<username/email>','<password>','<userkey>');

$prospect->setEntity( PardotAPI::ENTITY_PROSPECT );
$prospect->setValidFields( $validFields );

$prospect->loadById('<ID>');

echo $prospect->first_name . ' ' . $prospect->last_name . "({$prospect->job_title})" ;
```

## License

PardotPHPAPI is licensed under the MIT License. See the LICENSE file for details.

In the spirit of open source, use of this library for evil is discouraged.