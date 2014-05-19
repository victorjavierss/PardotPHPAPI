<?php
/**
 * 
 * User: vjavier
 * Date: 5/16/14
 * Time: 3:59 PM
 */

namespace PardotAPI;

class LoginAPI extends PardotAPI{

    protected $_pardotAction = 'login';

    public function getApiKey(){
        $url = self::PARDOT_API_URL . '/' . $this->_pardotAction . '/version/' . self::PARDOT_API_VERSION;
        $params[ self::PARDOT_USERKEY  ] = $this->_pardotUserKey;
        $params[ self::PARDOT_USERNAME ] = $this->_pardotUsername;
        $params[ self::PARDOT_PASSWORD ] = $this->_pardotPassword;
        return $this->_callPardotApi($url, $params)->api_key->__toString();
    }

} 