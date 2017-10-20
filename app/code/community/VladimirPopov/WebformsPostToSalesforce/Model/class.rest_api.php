<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author le xuan chien
 */
class RestApi {

    protected $dcAPI = 'us10';
    protected $keyAPI = '89419f88f47d827875a3123085e241be';
    protected $baseUrl = 'https://{%s}.api.mailchimp.com/2.0/';
    protected $urlRequest = '';

    /**
     * 
     * @param type $config 
     */
    public function __construct($config = array()) {
        if (isset($config['dcAPI']) && isset($config['keyAPI'])) {
            $this->dcAPI = $config['dcAPI'];
            $this->keyAPI = $config['keyAPI'];
            
        } 
        $this->urlRequest = str_replace('{%s}',$this->dcAPI,$this->baseUrl);///printf($this->baseUrl, $this->dcAPI);
        
    }

    /**
     * Request
     * @param type $args
     * @return type 
     */
    public function request($args) {
        if (!isset($args['method'])) {
            $args['method'] = 'POST';
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $arrHeader = array(
            //'X-Parse-Application-Id: ' . $this->appId,
            // 'X-Parse-REST-API-Key: ' . $this->restKey,
            'Content-Type: application/json'
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $arrHeader);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $args['method']);
        

        curl_setopt($curl, CURLOPT_URL, $args['url']);

        if ($args['method'] == 'PUT' || $args['method'] == 'POST') {
            $postData = json_encode($args['requestData']);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        } else {
            $postData = $args['requestData'];

            if (count($postData) > 0) {
                $query = http_build_query($postData, '', '&');
                curl_setopt($curl, CURLOPT_URL, $args['url'] . '?' . $query);
            }
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        ob_start();
        curl_exec($curl);
        //echo curl_error($curl);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    /**
     * Get info by objectId
     * @param type $args['className','objectId']
     * @return type 
     */
    public function runMethod($args, $pathMethod = 'helper/ping.json') {
        $url = $this->urlRequest . $pathMethod;
       // echo $url;
        //exit();
        $params = array(
            'url' => $url, //$args['className'] . '/' . $args['objectId'],
            'method' => 'post',
            'requestData' => $args,
            
        );
        return $this->request($params);
    }

    /**
     * Convert image
     * @param type $image
     * @return string 
     */
    public function convertBinary($image) {
        if (file_exists($image)) {
            $data = fopen($image, 'rb');
            $size = filesize($image);
            $contents = fread($data, $size);
            fclose($data);
            return $contents;
        } else {
            return "";
        }
    }

}

?>
