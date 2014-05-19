<?php
/**
 * Prospect Specilization for the PardotAPI
 * User: vjavier
 * Date: 5/16/14
 * Time: 3:59 PM
 */

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
        'company'   => true,
        'website'   => true,
        'job_title'   => true,
        'department'  => true,
        'country'     => true,
        'address_one' => true,
        'address_two' => true,
        'city'  => true,
        'state' => true,
        'territory' => true,
        'zip'    => true,
        'phone'  => true,
        'fax'    => true,
        'source' => true,
        'employees' => true,
        'industry'  => true,
        'comments'  => true,
        'notes'     => true,
        'score'     => true,
        'annual_revenue'      => true,
        'prospect_account_id' => true,
        'years_in_business'   => true,
        'is_do_not_email' => true,
        'is_do_not_call'  => true,
        'is_reviewed'  => true,
        'is_starred'   => true,
    );


    /**
     * @var string Entity we want to query in Pardot
     */
    protected $_pardotEntity = 'prospect';
} 