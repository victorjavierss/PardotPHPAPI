# PardotPHPAPI

PardotPHPAPI is a php class that makes easier the integration of Pardot® in your website.
It is a wrapper for common functions such as read, create and update.

## Requirements
PHP 5.3

## Usage
```php
<?php
include_once 'lib/Pardot/PardotAPI.php';
include_once 'lib/Pardot/LoginAPI.php';

use \PardotAPI\PardotAPI;

$validFields = array( 'id' => false, 'campaign_id' => true, 'salutation' => true, 'first_name' => true,
                    'last_name' => true, 'email' => true, 'password' => true, 'company'=> true,
                    'website' => true, 'job_title' => true, 'department' => true, 'country' => true,
                    'address_one' => true, 'address_two' => true, 'city' => true, 'state' => true, 'territory' => true,
                    'zip' => true, 'phone' => true, 'fax' => true, 'source' => true, 'employees' => true,
                    'industry' => true, 'comments'  => true, 'notes'     => true, 'score' => true,
                    'annual_revenue' => true, 'prospect_account_id' => true, 'years_in_business' => true,
                    'is_do_not_email' => true, 'is_do_not_call' => true, 'is_reviewed'  => true, 'is_starred' => true,
        );

$prospect = new PardotAPI('<username/email>','<password>','<userkey>');

$prospect->setEntity( PardotAPI::ENTITY_PROSPECT );
$prospect->setValidFields( $validFields );

$prospect->loadById('<ID>');

echo $prospect->first_name . ' ' . $prospect->last_name . "({$prospect->job_title})" ;
```

### Extending PardotAPI
You may extend the PardotAPI for specific entity like below: 

```php
<?php
namespace PardotAPI;

class ProspectPardot extends PardotAPI{
	 /**
     * @var array Valid editable fields for a prospect
     */
    protected $_validFields = array(
        'id' => false,
        'campaign_id' => true,
        'salutation'  => true,
        'first_name'  => true,
        'last_name' => true,
        'email'     => true,
        'password'  => true,
		...
		);
		
	/**
     * @var string Entity we want to query in Pardot
     */
    protected $_pardotEntity = 'prospect';
}
```

So you may use it as:


```php
<?php
include_once 'lib/Pardot/PardotAPI.php';
include_once 'lib/Pardot/LoginAPI.php';
include_once 'lib/Pardot/ProspectPardot.php';

use \PardotAPI\ProspectPardot;

$prospect = new ProspectPardot('<username/email>','<password>','<userkey>');
$prospect->loadById('<ID>');

echo $prospect->first_name . ' ' . $prospect->last_name . "({$prospect->job_title})" ;

$prospect->job_title = 'Web Developer';

if ( $prospect->save() ){
	echo " => Save success!!";
}else{
    echo " => Save error :(";
}
```

## License

PardotPHPAPI is licensed under the MIT License. See the LICENSE file for details.

In the spirit of open source, use of this library for evil is discouraged.