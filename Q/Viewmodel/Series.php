<?php

namespace Q\ViewModel;

use \Q\Api\HttpDefaultHandler as HttpDefaultHandler;
use \Q\Api\TheTvDb\Services\Authentication\Request\BearerToken as BearerToken;

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

// return the objects in question 

class Series {

    public $oServiceHandler;
    public $oSecurityService;
    public $seriesId;
    public $response;

    public function __construct($seriesId, HttpDefaultHandler $oServiceHandler, BearerToken $oSecurityService) {

        $this->seriesId = $seriesId;
        $this->oServiceHandler = $oServiceHandler;
        $this->oSecurityService = $oSecurityService;
    }

    
    public function getInfo() {

        $seriesInfo = \Q\Api\TheTvDb\Services\Series\Request\Info::create()
                ->setHttpServiceHandler($this->oServiceHandler)
                ->setSecurityService($this->oSecurityService)
                ->fetchAndAddSecurityHeaders()
                ->setParams($this->seriesId)
                ->callService();

        $this->response = $seriesInfo->getResponse();

        return $this;
    }

    /**
     * gets and sets an array of episodes for class $seriesId
     * @return $this
     */
    public function getEpisodes() {

        //first get the search data 
        $seriesEpisodes = \Q\Api\TheTvDb\Services\Series\Request\Episodes::create()
                ->setHttpServiceHandler($this->oServiceHandler)
                ->setSecurityService($this->oSecurityService)
                ->fetchAndAddSecurityHeaders()
                ->setParams($this->seriesId)//paginateable
                ->callService();

        $this->response = $seriesEpisodes->getResponse();

        return $this;
    }

    //should abstract this out
    public function json() {
        return json_encode($this->response);
    }

}

//factory this should be a routing pattern
if (isset($_GET['id']) != '' && isset($_GET['action']) != '') {

    $oServiceHandler = HttpDefaultHandler::create();
    $oSecurityService = BearerToken::create();
    $series = new Series($_GET['id'], $oServiceHandler, $oSecurityService);

    switch ($_GET['action']) {
        case 'episodes':
            echo $series->getEpisodes()->json();
            exit();
        case 'info':
            echo $series->getInfo()->json();
            exit();
        default :
            return json_encode(['data' => ['message' => 'empty']]);
    }
}        
