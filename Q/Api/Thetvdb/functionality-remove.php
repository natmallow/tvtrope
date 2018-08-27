<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//include $_SERVER['DOCUMENT_ROOT'].'/q/lazyloader.php';

//use \Models\Adc\Api\Classes\Curloptions as AliasBase;
//
//AliasBase\Base::setOverwriteCurrentOptions(false);
//AliasBase\Media::init();
//AliasBase\DefaultValues::post();
//
////$test->init();
//echo '<pre>';
//print_r(AliasBase\Base::getACurlOptions());
 echo 'here <br>';


$oServiceHandler = Q\Api\HttpDefaultHandler::create();
$oSecurityService = Q\Api\TheTvDb\Services\Authentication\Request\BearerToken::create();

//$token = Q\Api\TheTvDb\Services\Authentication\Request\BearerToken::create()
//        ->setHttpServiceHandler($oServiceHandler) 
//        ->setParams()
//        ->callService();

$series = Q\Api\TheTvDb\Services\Search\Request\Search::create()
        ->setHttpServiceHandler($oServiceHandler) 
        ->setSecurityService($oSecurityService)
        ->fetchAndAddSecurityHeaders()
        ->setParams('smurfs')
        ->callService();


$series->getResponse();

$thetvdbId = $series->getResponse()->data[0]->id;


$seriesId = Q\Api\TheTvDb\Services\Series\Request\Info::create()
        ->setHttpServiceHandler($oServiceHandler) 
        ->setSecurityService($oSecurityService)
        ->fetchAndAddSecurityHeaders()
        ->setParams($thetvdbId)
        ->callService();


$imdbId = $seriesId->getResponse()->data->imdbId; 

$seriesEpisodes = Q\Api\TheTvDb\Services\Series\Request\Episodes::create()
        ->setHttpServiceHandler($oServiceHandler) 
        ->setSecurityService($oSecurityService)
        ->fetchAndAddSecurityHeaders()
        ->setParams($thetvdbId)//paginate
        ->callService();


$episodes =  $seriesEpisodes->getResponse(); 

echo '<pre>';
print_r($imdbId);
print_r($episodes);
//print_r($seriesId->getResponse());

echo '</pre>';


