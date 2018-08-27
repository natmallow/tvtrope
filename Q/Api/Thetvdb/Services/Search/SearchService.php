<?php

namespace Q\Api\TheTvDb\Services\Search;



use Q\Api\HttpDefaultHandler;

use Q\Api\TheTvDb\Services\Authentication\SecurityService;

abstract class SearchService {

    protected $oHttpServiceHandler;
    protected $oSecurityService;

    /**
     * @return \static object
     */
    static public function create() {
        return new static();
    }

    /**
     * 
     * @param HttpDefaultHandler $oHttpServiceHandler
     * @return \Model\Api\Adc\Service\Docusign\DocusignService
     */
    public function setHttpServiceHandler(HttpDefaultHandler $oHttpServiceHandler) {
        $this->oHttpServiceHandler = $oHttpServiceHandler;
        return $this;
    }

    /**
     * 
     * @param SecurityService $oSecurityService
     * @return \Model\Api\Adc\Service\Docusign\DocusignService
     */
    public function setSecurityService(SecurityService $oSecurityService) {
        $this->oSecurityService = $oSecurityService;
        return $this;
    }

    /**
     * Calls the Security API
     * Sets Headers
     * @return \Model\Api\Adc\Service\Docusign\DocusignService
     */
    public function fetchAndAddSecurityHeaders() {

        /**
         * gets the security and sets it to header
         */
        $this->oSecurityService->setHttpServiceHandler($this->oHttpServiceHandler)->setParams();

        /**
         *  add to header for next call
         */
        $this->oHttpServiceHandler->addAHeaders(
                $this->oSecurityService->getTokenHeaders()
        );

        return $this;
    }

    /**
     * 
     * @return $this
     */
    public function callService() {
	$this->oHttpServiceHandler->requestDataGeneric();
	return $this;
    }
    
    /**
     * Get the response from the httpBase Class
     * @return type string
     */
    public function getResponse() {
        return $this->oHttpServiceHandler->getAResponse();
    }

}