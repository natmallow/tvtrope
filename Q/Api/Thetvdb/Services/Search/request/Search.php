<?php

namespace Q\Api\TheTvDb\Services\Search\Request;

use Q\Api\TheTvDb\Services\Search\SearchService;
use \Q\Api\MessageResource as MessageResource;

class Search extends SearchService {

    /**
     * need to document these {version}
     */
    public static $host = 'https://api.thetvdb.com/search/series';

    public function setParams($nameQuery) {
        
        $this->oHttpServiceHandler->setSHost(self::$host);
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
                    'name' => $nameQuery
                )
        );

        return $this;
    }

}
