<?php

/**
 * Class support for requesting to a server
 * CreateDate: 22 February 2011
 * Author: Khanh Huy
 * Email :khanhhuyna@gmail.com
 */
class FgcRequest {

    var $url;

    function __construct($url = null) {
        $this->url = $url;
    }

    function __destruct() {
        
    }

    /**
     *
     * @param string $url
     * @param boolean $synchronize .Define whether the script return immediately without waiting for a output from server
     * or require to get output from server.
     * @param string $requestType
     * @return mixed
     */
    function request($url = null, $synchronize = false, $requestType = 'POST') {
        if ($url === null)
            $url = $this->url;

        if (empty($url))
            return;

        if ($synchronize) {
            $fp = fopen($url, 'r');
            if (!$fp) {
                return false;
            } else {
                $return = '';
                while (!feof($fp)) {
                    $return .= fgets($fp, 1024);
                }
            }
            fclose($fp);
            return $return;
        } else {
            $parts = parse_url($url);

            $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
            if (!$fp) {
                return false;
            } else {

                $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
                $out.= "Host: " . $parts['host'] . "\r\n";
                $out.= "Connection: Close\r\n";
                if (isset($parts['query'])) {
                    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
                    $out.= "Content-Length: " . strlen($parts['query']) . "\r\n";
                }
                $out.= "\r\n";
                if (isset($parts['query']))
                    $out.= $parts['query'];
                fwrite($fp, $out);
                fclose($fp);
                return true;
            }
        }
    }

    /**
     *
     * @param type $name
     * @param type $default
     * @param type $type
     * @return type 
     */
    function getVar($name, $default = NULL, $type = 'request') {
        $type = strtoupper($type);
        switch ($type) {
            case 'GET':
                return $_GET[$name] ? $_GET[$name] : $default;

                break;
            case 'POST':
                return $_POST[$name] ? $_POST[$name] : $default;
                break;
            case 'PUT':
                return $_PUT[$name] ? $_PUT[$name] : $default;
                break;
            case 'REQUEST':
                return $_REQUEST[$name] ? $_REQUEST[$name] : $default;
                break;
            default:
                return $default;
        }
    }

    /**
     *
     * @param type $name
     * @param type $type
     * @return type 
     */
    function get($default = array(), $type = 'request') {
        $type = strtoupper($type);
        switch ($type) {
            case 'GET':
                return $_GET ? $_GET : $default;
                break;
            case 'POST':
                return $_POST ? $_POST : $default;
                break;
            case 'PUT':
                return $_PUT ? $_PUT : $default;
                break;
            case 'REQUEST':
                return $_REQUEST ? $_REQUEST : $default;
                break;
            default:
                return $default;
        }
    }

    /**
     *
     * @param type $name
     * @param type $value
     * @param type $type
     * @return type 
     */
    function setVar($name, $value = NULL, $type = 'request') {
        switch (strtolower($type)) {
            case 'get':
                return $_GET[$name] = $value;
                break;
            case 'post':
                return $_POST[$name] = $value;
                break;
            case 'put':
                return $_PUT[$name] = $value;
                break;
            case 'request':
                return $_REQUEST[$name] = $value;
                break;
            default:
                return null;
        }
    }

    /**
     *
     * @param type $vars
     * @param type $type 
     */
    function setVars($vars, $type = 'REQUEST') {
        if (!empty($vars)) {
            foreach ($vars as $name => $var) {
                self::setVar($name, $var, $type);
            }
        }
    }

    function httpGetRequest($url, $postvars = '', $curl_timeout = 20) {
// echo $url;
// echo $postvars;
        $header = get_headers($url);
        print_r($header);
        echo(dirname(dirname(__FILE__)) . '/var/tmp');
        exit();
        if (!function_exists('curl_init')) {
            die('Sorry cURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($postvars) {
// parse_str($postvars, $datas);
            curl_setopt($ch, CURLOPT_POST, 1);                //0 for a get request
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, $curl_timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//curl_setopt($ch, CURLOPT_COOKIESESSION, true);
//curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie-name');  //could be empty, but cause problems on some hosts
//curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(dirname(__FILE__)).'/var/tmp');  //could be empty, but cause problems on some hosts
// $answer = curl_exec($ch);
        $response = curl_exec($ch);
        print "curl response is:" . $response;
        exit();
        curl_close($ch);
        if ($response === false)
            return false;
//die("Could not fetch response " . $url);
        return $response;
    }

    public function open_http($url, $method = false, $params = null) {
        $cookie_file_path = $tmp_fname = tempnam("/tmp", "COOKIE"); //dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cookie_test.txt';
        //echo $cookie_file_path;
        if (!function_exists('curl_init')) {
            die('ERROR: CURL library not found!');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $method);
        if ($method == true && isset($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Content-Length: ' . strlen($params),
//            'Cache-Control: no-store, no-cache, must-revalidate',
//            "Expires: " . date("r")
//        ));
//        $header[0] = "Accept: text/html,application/xhtml+xml,application/xml,";
//        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
//        $header[] = 'Content-Length: ' . strlen($params);
//        $header[] = "Cache-Control: max-age=0";
//        $header[] = "Connection: keep-alive";
//       // $header[] = "Keep-Alive: 300";
//        $header[] = "Cookie: test=cookie";
//        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
//        $header[] = "Accept-Language: de-de,de;q=0.8,en-us;q=0.5,en;q=0.3";
//        $header[] = "Accept-Encoding: gzip, deflate";
//        $header[] = "Pragma: ";
        $header[] = "Accept:	text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        $header[] = "Accept-Encoding:	gzip, deflate";
        $header[] = "Accept-Language:	en-US,en;q=0.5";
        $header[] = "Connection:	keep-alive";
        $header[] = "Cookie:	__utma=196110824.306776340.1416365378.1416365378.1416367497.2; __utmz=196110824.1416365378.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)";
        $header[] = "Host:	www.upssolutions.com.au";
        $header[] = "User-Agent:	Mozilla/5.0 (Windows NT 6.1; WOW64; rv:33.0) Gecko/20100101 Firefox/33.0 FirePHP/0.7.4";
        $header[] = "x-insight:	activate";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

// 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
       // curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

        $result = curl_exec($ch);
//        $info = curl_getinfo($ch);
//        print_r($info);
//        exit();
        curl_close($ch);
        return $result;
    }

}

?>
