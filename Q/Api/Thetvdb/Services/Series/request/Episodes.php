<?php

namespace Q\Api\TheTvDb\Services\Series\Request;

use Q\Api\TheTvDb\Services\Series\SeriesService;
use \Q\Api\MessageResource as MessageResource;

class Episodes extends SeriesService {

    /**
     * need to document these {version}
     */
    public static $host = 'https://api.thetvdb.com/series/%d/episodes';

    public function setParams($seriesId, $page='1') {
        
        $host = sprintf(self::$host, $seriesId);
                
        $this->oHttpServiceHandler->setSHost($host);
        $this->oHttpServiceHandler->setSProtocol(MessageResource::PROTOCAL_HTTPS);
        $this->oHttpServiceHandler->setSHttpProtocol(MessageResource::CURL_HTTP_VERSION_1_1);
        $this->oHttpServiceHandler->setSHttpRequestMethod(MessageResource::REQUEST_METHOD_GET);
        $this->oHttpServiceHandler->addAHeaders(
                array(
                    MessageResource::HEADER_ACCEPT => MessageResource::HEADER_VALUE_APP_JSON
                )
        );
        $this->oHttpServiceHandler->setAParams(
                array(
                    'page' => $page
                )
        );

        return $this;
    }

}
