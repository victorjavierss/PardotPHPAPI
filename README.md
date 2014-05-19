# PardotPHPAPI

A PHP client for the Pardot REST API.

**Requirements**


PHP 5.3

## Usage
 - [Get API Key](#get-api-key)
 - [Load entity](#load-entity)
 - [Create/Update entity](#create-update-entity)

### Authentication
The first thing you'll want to do is include the PardotAPI class and create a new instance of the client.

You will need your username, password and user key. These can be found in the account settings when logged into [pi.pardot.com](http://pi.pardot.com/).

This will be achieved like this:

```php
<?php
include_once 'lib/Pardot/PardotAPI.php';
use \PardotAPI\PardotAPI;
$entity = new PardotAPI('<username/email>','<password>','<userkey>');
```

### Get API Key
For getting the api key you should authenticate through the REST service, but with this class you don't have to worry about it ;).

### Load entity
An entity is an `prospect`, `opportunity`, `users`, `visit` and a `visitor`, once you have the instance of PardotAPI set what entity you will be working for example:

```php
<?php
$prospect->setEntity( PardotAPI::ENTITY_PROSPECT );
```

The PardotAPI class has constants declared for achieve this, the constats may used as follows:

 - `PardotAPI::ENTITY_PROSPECT`
 - `PardotAPI::ENTITY_OPPORTUNITY`
 - `PardotAPI::ENTITY_USERS`
 - `PardotAPI::ENTITY_VISIT`
 - `PardotAPI::ENTITY_VISITOR`

This entities may be loaded by using the `loadById` or `loadByEmail` methods.


**Arguments**


 - The `loadById` method recieves only the `$id` of the prospect/opportunity/users/visit/visitor.
 - The `loadByEmail` method recieves only the `$email` of the prospect/opportunity/users.



### Full example
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

**Extending PardotAPI**
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

MIT © Addy Osmani, Sindre Sorhus, Pascal Hartig, Stephen Sawchuk.