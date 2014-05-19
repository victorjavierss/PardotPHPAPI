<?php
    ob_start();

    include_once 'lib/Pardot/PardotAPI.php';
    include_once 'lib/Pardot/LoginAPI.php';
    include_once 'lib/Pardot/ProspectPardot.php';

    use \lib\PardotAPI\PardotAPI;

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

   $prospect->job_title = 'Web Developer';

   if ( $prospect->save() ){
       echo " => Save success!!";
   }else{
       echo " => Save error :(";
   }