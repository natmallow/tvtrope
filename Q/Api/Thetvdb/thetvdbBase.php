<?php

namespace Q\Api\TheTvDb;

/**
 * leave the long name for now so that we 
 */
abstract class ThetvdbBase {

    public $sVersion = 'v1';
    public $sAuthorizationType = 'user token';
    public $sUri;

    /**
     * Api_service Arguments for instatiation
     */
    protected $client_id;
    protected $client_secret;
    protected $username;
    protected $password;
    protected $use_session;

    /**
     * 
     * @param type $api
     * @return \static
     */
    public static function create($api) {
        return new static($api);
    }

    /**
     * 
     * @return $this
     */
    public function prepareApiCall() {

        $oApi = new $this->oApi($this->client_id, $this->client_secret, $this->username, $this->password, $this->use_session);
        $oApi->setBaseUrl($this->sHost);
        $this->addVersionToUri();

        $this->aApiCallParams['method'] = $this->sMethod;
        $this->aApiCallParams['headers'] = $this->aHeader;
        $this->aApiCallParams['params'] = $this->aParams;
        $this->aApiCallParams['url'] = $this->sUri; //not a url but leveraging api_services
        //set object back 
        $this->oApi = $oApi;

        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getResponse() {
        return $this->oResponse;
    }

    /**
     * for versioning it v2/path v3/path
     */
    public function addVersionToUri() {
        $this->sUri = str_replace("{version}", $this->sVersion, $this->sUri);
    }

    /**
     * Modifiers setters and getters
     */
    public function setCurlOptions(\APIClasses\APICurlOptions\CurlOptions $oCurlOptions) {
        $this->aCurlOptions = $this->getACurlOptions();
    }

    /**
     * 
     * @param type $sVersion
     */
    public function setSVersion($sVersion) {
        $this->sVersion = $sVersion;
    }

    /**
     * 
     * @param type $sAuthorizationType
     */
    public function setSAuthorizationType($sAuthorizationType) {
        $this->sAuthorizationType = $sAuthorizationType;
    }

    /**
     * 
     * @param type $sHost
     */
    public function setSHost($sHost) {
        $this->sHost = $sHost;
    }

    public function getOResponse() {
        return $this->oResponse;
    }

    public function getClient_id() {
        return $this->client_id;
    }

    public function getClient_secret() {
        return $this->client_secret;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getUse_session() {
        return $this->use_session;
    }

    public function setOResponse($oResponse) {
        $this->oResponse = $oResponse;
    }

    public function setClient_id($client_id) {
        $this->client_id = $client_id;
    }

    public function setClient_secret($client_secret) {
        $this->client_secret = $client_secret;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setUse_session($use_session) {
        $this->use_session = $use_session;
    }

}
