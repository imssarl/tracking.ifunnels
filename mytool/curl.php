<?php
/**

 *  about

 * 

 *  author:  Dan Friedman

 *  file:    class.curl.php

 *  version: 1.0.1 (November 12th, 2006)

 *  site:    www.dan-friedman.com

 *  email:   dan@dan-friedman.com

**/



class CURL {



    var $ch;

    var $debug    = false;

    var $errormsg = false;

    

    function CURL ($debug = false) {

        //echo $debug;

        $this->ch = curl_init();

       // curl_setopt($this->ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        //curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);

        //curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);

        //curl_setopt($this->ch, CURLOPT_COOKIEJAR, 'cookie.txt');

       //curl_setopt($this->ch, CURLOPT_COOKIEFILE, 'cookie.txt');

    }



    function set_referrer($referrer_url) {

        curl_setopt($this->ch, CURLOPT_REFERER, $referrer_url);

    }



    function set_user_agent($useragent) {

        curl_setopt($this->ch, CURLOPT_USERAGENT, $useragent);

    }



    function set_error_message($message) {

        $this->errormsg = $message;

    }



    function request($method, $url, $vars) {

        curl_setopt($this->ch, CURLOPT_URL, $url);

        if ($method == 'POST') {

            curl_setopt($this->ch, CURLOPT_POST, 1);

            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $vars);

        }

        $data = curl_exec($this->ch);

        if (curl_errno($this->ch)) {

            if ($this->debug) {

                $this->error  = "<pre>An Error Occured\n";

                $this->error .= "------------------\n";

                $this->error .= "Error number: " .curl_errno($this->ch) ."\n";

                $this->error .= "Error message: " .curl_error($this->ch)."\n</pre>";

            } else {

                if ($this->errormsg) $this->error = $this->errormsg;

                else $this->error = '';

            }

            return $this->error;

        } else {

            return $data;

        }

    }



    function get($url) {

        return $this->request('GET', $url, 'NULL');

    }



    function post($url, $vars) {

        return $this->request('POST', $url, $vars);

    }

    

    function post_array($url, $vars_array) {

        foreach ($vars_array as $key => $value) {

            $var[] = $key .'='. urlencode($value);

        }

        $vars = implode('&', $var);

        return $this->request('POST', $url, $vars);

    }



}



$curl = &new CURL();



?> 