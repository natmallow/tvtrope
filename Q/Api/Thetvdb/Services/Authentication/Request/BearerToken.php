<?php

namespace Q\Api\TheTvDb\Services\Authentication\Request;

use Q\Api\HttpDefaultHandler,
    Q\Api\Thetvdb\Services\Authentication\SecurityService;
use Q\Api\MessageResource as MessageResource;


class BearerToken extends SecurityService {

    public static $host = 'https://api.thetvdb.com/login';

    public function setParams() {
        
        $apikey = 'LENC7CQ2WUH4PB87';
        $username = 'jfrs506zo';
        $userkey = '17MSCXAHS7FO09C5';
        
        $this->token_type = 'bearer';

        $this->oHttpServiceHandler->setSHost(self::$host);
        $this->oHttpServiceHandler->setSProtocol(MessageResource::PROTOCAL_HTTPS);
        $this->oHttpServiceHandler->setSHttpRequestMethod(MessageResource::REQUEST_METHOD_POST);
        $this->oHttpServiceHandler->setSHttpProtocol(MessageResource::CURL_HTTP_VERSION_1_1);
        $this->oHttpServiceHandler->setAHeaders(
                array(
                    'Content-Type' => MessageResource::HEADER_VALUE_APP_JSON
                )
        );
        $this->oHttpServiceHandler->setAParams(
                array(
                    'apikey' => $apikey,
                    'username' => $username,
                    'userkey' => $userkey
                )
        );
        
        return $this;
    }
}
