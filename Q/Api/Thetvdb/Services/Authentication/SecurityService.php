<?php

namespace Q\Api\TheTvDb\Services\Authentication;

use Q\Api\HttpDefaultHandler;

abstract class SecurityService {

    protected $oHttpServiceHandler;

    //Security service token info
    protected $access_token = "";
    protected $token_type = "";
    protected $expires_in = "";
    protected $scope = "";
    
    //added information
    protected $aAuthToken;
    protected $oResponse;
    protected $aTokenHeaders;


    /*
     * Timer to keep track of the token expiry
     * set default to zero
     */
    protected $iExpireTime = 0;
    protected $rawResult;
    protected $respData;

    /**
     * @return \static object
     */
    static public function create() {
        return new static();
    }

    /**
     * 
     * @param HttpDefaultHandler $oHttpServiceHandler
     * @return \Q\Api\TheTvDb\Services\Authentication
     */
    public function setHttpServiceHandler(\Q\Api\HttpDefaultHandler $oHttpServiceHandler) {
        $this->oHttpServiceHandler = $oHttpServiceHandler;
        return $this;
    }

    /**
     * Fetch data using HttpDefaultHandler and no special data processing here. 
     * If success, response result set to rawResult; if not set the empty to the rawResult and respData.
     */
    private function fetchData() {

        try {
            $this->rawResult = $this->oHttpServiceHandler->requestDataGeneric();
        } catch (\Exception $e) {
            //use a logger
            echo 'Caught exception: Wrong code'.$e->getMessage();
        }
    }

    /**
     * TODO: Refactoring
     * Needs work !!
     * @return boolean
     */
    public function fetchBoolean() {
            $this->fetchData();
            $respData = json_decode($this->rawResult, true);
            $statusArr = $respData['status'];
            $message = $statusArr['message'];
            if (trim($message) == "success") {
                return true;
            } else {
                return false;
            }
    }

    /**
     * Extract the response Data into class properties/members
     */
    public function extractData() {
        if (is_array($this->respData) && count($this->respData) > 0) {
            foreach ($this->respData as $key => $value) {
                $this->{$key} = $value;
            }
        }
        return $this;
    }

    /**
     * Call to the security service API and extract the data into corresponding class members.
     */
    public function callService() {

        try {
            $this->fetchData();
            $this->respData = json_decode($this->rawResult, true);
            $this->extractData();
            $this->iExpireTime = time() + (int) (24 * 60 * 60);
            $this->setTokenHeaders();
        } catch (Exception $e) {
            //add exception handler logger
        }

        return $this;
    }

    /**
     * Check the expiry of current token.
     */
    private function isCurrentTokenExpired() {
        return time() >= $this->iExpireTime;
    }

    /**
     * Sets the security service token.
     * 
     * @param string $format
     * @return Array (Authorization, Accept, Content-Type)
     */
    private function setTokenHeaders() {

        $this->aTokenHeaders = array(
            'Authorization' => ucfirst($this->token_type) . ' ' . $this->token           
        );
    }

    public function getTokenHeaders() {
        if ($this->isCurrentTokenExpired()) {
            $this->callService();
        }

        return $this->aTokenHeaders;
    }

    /**
     * Convenient method to retrieve the raw result; only good after fetchData() call.
     * 
     * @return mixed <string, \APIHandlers\mixed, mixed>
     */
    public function getRawData() {
        return $this->rawResult;
    }

    /**
     * convenient method to retrieve the response data; only good after executeServiceCall() call.
     * @return mixed <string, mixed>
     */
    public function getRespData() {
        return $this->respData;
    }

}
