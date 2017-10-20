<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_SocialLogin
 * @copyright   Copyright (c) 2014 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


class Plumrocket_SocialLogin_Model_Live extends Plumrocket_SocialLogin_Model_Account
{
	protected $_type = 'live';
	
    protected $_url = 'https://login.live.com/oauth20_authorize.srf';

	protected $_fields = array(
					'user_id' => 'id',
		            'firstname' => 'first_name',
		            'lastname' => 'last_name',
		            'email' => 'email',
		            'dob' => 'birthday',
                    'gender' => 'gender',
                    'photo' => 'photo',
				);

    protected $_dob = array('day', 'month', 'year', '/');
    protected $_gender = array('m', 'f');

	protected $_buttonLinkParams = array(
					'scope' => 'wl.signin wl.basic wl.emails wl.birthday',
                    // 'display' => 'popup',
				);

    protected $_popupSize = array(650, 500);

	public function _construct()
    {      
        parent::_construct();
        
        $this->_buttonLinkParams['scope'] = urlencode($this->_buttonLinkParams['scope']);
        $this->_buttonLinkParams = array_merge($this->_buttonLinkParams, array(
            'client_id'     => $this->_applicationId,
            'redirect_uri'  => $this->_redirectUri,
            'response_type' => $this->_responseType
        ));
    }

    public function loadUserData($response)
    {
    	if(empty($response)) {
            return false;
        }

        $data = array();

        $params = array(
            'client_id' => $this->_applicationId,
            'client_secret' => $this->_secret,
            'redirect_uri' => $this->_redirectUri,
            'code' => $response,
            'grant_type' => 'authorization_code',
        );

        $token = null;
        if($response = $this->_call('https://login.live.com/oauth20_token.srf', $params)) {
            $token = json_decode($response, true);
        }
        $this->_setLog($token, true);

        if (isset($token['access_token'])) {
            $params = array(
                'access_token' => $token['access_token'],
            );

            if($response = $this->_call('https://apis.live.net/v5.0/me', $params)) {
                $data = json_decode($response, true);
            }
            $this->_setLog($data, true);
        }
 
        if(!$this->_userData = $this->_prepareData($data)) {
        	return false;
        }

        $this->_setLog($this->_userData, true);

        return true;
    }

    protected function _prepareData($data)
    {
    	if(empty($data['id'])) {
    		return false;
    	}

    	// Email.
    	$emails = $data['emails'];
    	if(!empty($emails['preferred'])) {
    		$data['email'] = $emails['preferred'];
    	}elseif(!empty($emails['account'])) {
    		$data['email'] = $emails['account'];
    	}elseif(!empty($emails['personal'])) {
    		$data['email'] = $emails['personal'];
    	}elseif(!empty($emails['business'])) {
    		$data['email'] = $emails['business'];
    	}

    	// Photo.
    	$data['photo'] = "https://apis.live.net/v5.0/{$data['id']}/picture";

    	// Birthday.
    	if(!empty($data['birth_day']) && !empty($data['birth_month'])) {
	    	$data['birthday'] = $data['birth_day'] .'/'. $data['birth_month'] .'/'. $data['birth_year'];
	    }
	    
        return parent::_prepareData($data);
    }

}