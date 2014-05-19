<?php
/**
 *
 * User: vjavier
 * Date: 5/16/14
 * Time: 3:58 PM
 */

namespace PardotAPI;

class PardotAPI {

    const PARDOT_API_URL = 'https://pi.pardot.com/api';
    const PARDOT_API_VERSION = '3';

    const EMAIL_FIELD = 'email';
    const ID_FIELD    = 'id';

    const PARDOT_USERNAME = 'email';
    const PARDOT_PASSWORD = 'password';
    const PARDOT_USERKEY  = 'user_key';
    const PARDOT_APIKEY   = 'api_key';

    const READ     = 'read';
    const CREATE   = 'create';
    const UPDATE   = 'update';
    const ASSIGN   = 'assign';
    const UNASSIGN = 'unassign';
    const UPSERT   = 'upsert';
    const DELETE   = 'delete';

    const STATUS_OK   = 'ok';
    const STATUS_FAIL = 'fail';

    const ENTITY_PROSPECT    = 'prospect';
    const ENTITY_OPPORTUNITY = 'opportunity';
    const ENTITY_USERS       = 'users';
    const ENTITY_VISIT       = 'visit';
    const ENTITY_VISITOR     = 'visitor';


    protected $_pardotEntity   = null;
    protected $_pardotUsername = null;
    protected $_pardotPassword = null;
    protected $_pardotUserKey  = null;

    /**
     * @var array The prospect data
     */
    protected $_data = array();

    /**
     * @var array Valid editable fields for a prospect
     */
    protected $_validFields = array();

    public function __construct( $username, $password, $userkey ){
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setUserKey($userkey);
    }

    public function setUsername( $username ){
        $this->_pardotUsername = $username;
    }

    public function setPassword( $password ){
        $this->_pardotPassword = $password;
    }

    public function setUserKey( $userkey ){
        $this->_pardotUserKey = $userkey;
    }

    public function setEntity( $entity ){
        $this->_pardotEntity = $entity;
    }

    public function setValidFields( $validFields ){
        $this->_validFields = $validFields;
    }

    protected function _callPardotApi($url, $data, $method = 'GET'){
        // build out the full url, with the query string attached.
        $queryString = http_build_query($data, null, '&');
        $queryStringSeparator = strpos($url, '?') !== false ? '&' : '?';
        $url .= $queryStringSeparator . $queryString;

        $curl_handle = curl_init($url);

        // wait 5 seconds to connect to the Pardot API, and 30
        // total seconds for everything to complete
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 30);

        // https only, please!
        curl_setopt($curl_handle, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);

        // ALWAYS verify SSL - this should NEVER be changed. 2 = strict verify
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 2);

        // return the result from the server as the return value of curl_exec instead of echoing it
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

        if ( strcasecmp($method, 'POST') === 0 ) {
            curl_setopt($curl_handle, CURLOPT_POST, true);
        } elseif ( strcasecmp($method, 'GET') !== 0 ) {
            // perhaps a DELETE?
            curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        }

        $pardotApiResponse = curl_exec($curl_handle);
        if ($pardotApiResponse === false) {
            // let's see what went wrong -- first look at curl
            $humanReadableError = curl_error($curl_handle);

            // you can also get the HTTP response code
            $httpResponseCode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

            // make sure to close your handle before you bug out!
            curl_close($curl_handle);

            throw new Exception("Unable to successfully complete Pardot API call to $url -- curl error: \"".
                "$humanReadableError\", HTTP response code was: $httpResponseCode");
        }

        // make sure to close your handle before bug out!
        curl_close($curl_handle);

        $response = simplexml_load_string($pardotApiResponse);

        if (  $response->attributes()->stat->__toString() !== self::STATUS_OK ){
            throw new \Exception('Error : ' . $response->err->__toString() );
        }

        return $response;
    }

    protected function _callApi( $action, $params, $method = 'GET' ){
        if ( ! $this->_pardotEntity  ){
            throw new \Exception ( "Entity was not setted" );
        }

        $url = self::PARDOT_API_URL . '/' . $this->_pardotEntity . '/version/' . self::PARDOT_API_VERSION . '/do/' . $action;

        $login = new LoginAPI($this->_pardotUsername, $this->_pardotPassword, $this->_pardotUserKey);

        $apiKey = $login -> getApiKey();

        $params[ self::PARDOT_USERKEY ] = $this->_pardotUserKey;
        $params[ self::PARDOT_APIKEY ]  = $apiKey;

        foreach($params as $key => $value){
            if( !$value ){
                unset($params[$key]);
            }
        }

        return $this->_callPardotApi( $url, $params, $method );
    }

    /**
     * Loads the data of a prospect according to the $fieldLoadBy parameter
     *
     * @param $fieldLoadBy The field we want to retrieve the data
     * @param $value The value of the field that we want to load
     * @return bool TRUE if the prospect was loaded correctly
     */
    protected function _load($fieldLoadBy, $value){
        try{
            $this->_bindData( $this->_callApi( self::READ, array($fieldLoadBy=>$value) ) );
            return true;
        }catch( \Exception $ex ){
            return false;
        }
    }

    protected  function _bindData( $data ){
        foreach( $this->_validFields as $field => $valid ){
            $this->_data[ $field ] = $data -> prospect -> { $field } -> __toString();
        }
    }

    /**
     * Loads the data of an entity by the id field
     * @param $id The prospect Id
     * @return bool TRUE if loaded correctly
     */
    public function loadById ( $id ){
        return $this->_load( self::ID_FIELD, $id );
    }

    /**
     * Loads the data of an entity by the email field
     * @param $email The prospect email
     * @return bool TRUE if loaded correctly
     */
    public function loadByEmail( $email ){
        return $this->_load( self::EMAIL_FIELD, $email);
    }

    /**
     * Creates or Updates a Pardot Entity
     * @return bool TRUE if the entity was saved successfully
     */
    public function save(){
        $action = isset( $this->_data[ self::ID_FIELD ] ) ? self::UPDATE : self::CREATE;
        try{
            $this->_bindData(  $this->_callApi($action, $this->_data) );
            return true;
        }catch ( \Exception $ex ){
            return false;
        }
    }

    /**
     * Obtains all the data retrieved by the load function
     * @return array
     */
    public function getData(){
        return $this->_data;
    }

    /**
     * @param $field The field retrieved from Pardot
     * @return string|bool The value of the field
     */
    public function __get($field){
        return isset ( $this->_data[ $field ] ) ? $this->_data[ $field ] : false;
    }

    /**
     * @param $field The field which value we want to set up
     * @param $value The value of the field
     */
    public function __set($field, $value){
        if( isset( $this->_validFields[ $field ] )
            && $this->_validFields[ $field ] ){
            $this->_data[ $field ] = $value;
        }
    }
}