<?php

namespace Q\ViewModel;

use    \Q\Api\HttpDefaultHandler as HttpDefaultHandler;
use    \Q\Api\TheTvDb\Services\Authentication\Request\BearerToken as BearerToken;

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

// return the objects in question 

class Search {

    public $oServiceHandler;
    public $oSecurityService;
    public $searchPhrase;
    public $response;
    public static $errorResponse = ['data' => ['message' => 'empty']];

    public function __construct($searchPhrase, HttpDefaultHandler $oServiceHandler, BearerToken $oSecurityService) {
        $this->searchPhrase = $searchPhrase;

        $this->oServiceHandler = $oServiceHandler;
        $this->oSecurityService = $oSecurityService;
        //move these 
        //$errorResponse = json_encode(['data' => ['message' => 'empty']]);
        
        return $this;
    }

    public function runSearch() {


        
        //first get the search data 
        $series = \Q\Api\TheTvDb\Services\Search\Request\Search::create()
                ->setHttpServiceHandler($this->oServiceHandler)
                ->setSecurityService($this->oSecurityService)
                ->fetchAndAddSecurityHeaders()
                ->setParams(urlencode($this->searchPhrase))
                ->callService();

        if (isset($series->getResponse()->data[0]->id) != '') {
            $this->response = json_encode($series->getResponse()->data[0]);
        } else {
           $this->response = json_encode(['data' => ['message' => 'empty']]);
        }
    }

}

if (isset($_GET['name']) != '') {
    $oServiceHandler = HttpDefaultHandler::create();
    $oSecurityService = BearerToken::create();
    $search = new search($_GET['name'],$oServiceHandler,$oSecurityService);
    $search->runSearch();
    
    print $search->response;
}