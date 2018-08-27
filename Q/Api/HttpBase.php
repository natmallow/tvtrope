<?php

namespace Q\Api;

/**
 * this is the base class 
 */
abstract class HttpBase {

    /**
     *
     * @var type 
     */
    protected $oLogger;

    /**
     * @var type 
     */
    protected $sProtocol = 'http';
    protected $sHost = '';
    protected $iPort = 80;
    protected $sPath = ''; //url
    protected $sHttpRequestMethod = ''; //POST : PUT : GET
    protected $sHttpProtocol = 'CURL_HTTP_VERSION_1_1'; //default 
    protected $aParams = array();
    protected $aHeaders = array();
    protected $aCurls = array();
    protected $aCurlBatchResponses = array();

    /**
     * 
     * @link http://php.net/manual/en/function.curl-setopt.php 
     * CURLOPT_CONNECTTIMEOUT : The number of seconds to wait while trying to connect. Use 0 to wait indefinitely. 
     * $iConTimeout 
     */
    protected $iConTimeout = 0;

    /**
     * 
     * @link http://php.net/manual/en/function.curl-setopt.php 
     * CURLOPT_TIMEOUT	The maximum number of seconds to allow cURL functions to execute.
     * @var interger $iTimeout 
     */
    protected $iTimeout = 0;

    /**
     * 
     * @link http://php.net/manual/en/function.curl-setopt-array.php 
     * multiple options for a cURL session. This function is useful for setting a large 
     * amount of cURL options without repetitively calling curl_setopt().
     * @var array type 
     */
    protected $aCurlOptions = array();

    /**
     *
     * After curl call
     */
    protected $sResponse = '';
    protected $aResponse = array();

    /**
     * 
     * @link http://php.net/manual/en/function.curl-getinfo.php
     */
    protected $aResponseInfo = array(); //curl_getinfo
    //not needed??
    protected $sResponseCurlError = ''; //curl_error
    protected $sResponseCurlErrorNumber = ''; //curl_errno

    /**
     *  Static Request Methods 
     */

    const HTTP_METHOD_GET = "GET";
    const HTTP_METHOD_POST = "POST";
    const HTTP_METHOD_PUT = "PUT";
    const HTTP_METHOD_DELETE = "DELETE";

    /**
     *  HTTP url definitions
     */
    const HTTP_PROTOCOL = "protocol";
    const HTTP_HOST = "host";
    const HTTP_PORT = "port";
    const HTTP_METHOD = "httpMethod";
    const HTTP_URL_PATH = "path";
    const URL_PATH_SEPERATOR = "/";

    /**
     * @return \static object
     */
    static public function create() {
        return new static();
    }

    public function getSProtocol() {
        return $this->sProtocol;
    }

    public function getSHost() {
        return $this->sHost;
    }

    public function getOLogger() {
        if (!is_object($this->oLogger)) {
            $this->setOLogger("rootLogger");
        }
        return $this->oLogger;
    }

    public function setOLogger($sLogger) {
        $this->oLogger = getPHPLogger($sLogger);
    }

    public function getIPort() {
        return $this->iPort;
    }

    public function getSPath() {
        return $this->sPath;
    }

    public function getSHttpRequestMethod() {
        return $this->sHttpRequestMethod;
    }

    public function getSHttpProtocol() {
        return $this->sHttpProtocol;
    }
    
    public function getAParams() {
        return $this->aParams;
    }

    public function getAHeaders() {
        return $this->aHeaders;
    }

    public function getIConTimeout() {
        return $this->iConTimeout;
    }

    public function getITimeout() {
        return $this->iTimeout;
    }

    public function getACurlOptions() {
        return $this->aCurlOptions;
    }

    public function getSResponse() {
        return $this->sResponse;
    }

    public function getAResponse() {
        return \json_decode($this->sResponse);
    }

    public function setSProtocol($sProtocol) {
        $this->sProtocol = $sProtocol;
    }

    public function setSHost($sHost) {
        $this->sHost = $sHost;
    }

    public function setIPort($iPort) {
        $this->iPort = $iPort;
    }

    public function setSPath($sPath) {
        $this->sPath = $sPath;
    }

    public function setSHttpRequestMethod($sHttpRequestMethod) {
        $this->sHttpRequestMethod = $sHttpRequestMethod;
    }
    
    public function setSHttpProtocol($sHttpProtocol) {
        return $this->sHttpProtocol = $sHttpProtocol;
    }
    
    public function setAParams($aParams) {
        $this->aParams = $aParams;
    }

    /**
     * Will append to existing headers
     * Rewriting key and values of existing headers
     * @param array $headers
     */
    public function addAHeaders($headers) {
        if (is_array($headers)) {
            if (count($this->aHeaders) == 0) {
                $this->aHeaders = $headers;
            } else {
                $this->aHeaders = array_replace($this->aHeaders, $headers);
            }
        } else {
            //replace with a logger
            throw new \Exception("addAHeaders: Headers must be in array format.");
        }
    }

    /**
     * Set Headers
     * Will over write existing headers
     * @param array $headers
     */
    public function setAHeaders($headers) {
        if (is_array($headers)) {
            $this->aHeaders = $headers;
        } else {
            //replace with a logger
            throw new \Exception("setAHeaders: Headers must be in array format.");
        }
    }

    public function setIConTimeout($iConTimeout) {
        $this->iConTimeout = $iConTimeout;
    }

    public function setITimeout($iTimeout) {
        $this->iTimeout = $iTimeout;
    }

    public function setSResponse($sResponse) {
        $this->sResponse = $sResponse;
    }

    public function setACurlOptions(array $aCurlOptions) {
        $this->aCurlOptions = $aCurlOptions;
    }

    /**
     * 
     * @param array $aCurlOptions
     */
    public function addACurlOptions(array $aCurlOptions) {
        //know array_replace perserves the key values
        $this->aCurlOptions = array_replace($aCurlOptions, $this->aCurlOptions);
    }

    /**
     * @return array
     */
    public function getABatchCallResponse() {
        return $this->aCurlBatchResponses;
    }

}
