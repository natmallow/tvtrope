<?php

namespace Q\Api;

/**
 * Description of MessageResource - this is over kill :P
 * All constant string information that pertains to API Calls Should go here
 * Should be in A-Z order and by grouping
 * 
 * @author nmallow
 */
class MessageResource {
	//put your code here no you put your code here 

	/**
	 * Header keys
	 */
	const HEADER_ACCEPT = 'Accept';
	const HEADER_AUTHORIZATION = 'Authorization';
	const HEADER_CONTENT_TYPE = 'Content-Type';
	const HEADER_CONTENT_LENGTH = 'Content-Length';

	/**
	 * Header Values
	 */
	const HEADER_VALUE_APP_JSON = 'application/json';
	
	/**
	 * Request Methods
	 */
	const REQUEST_METHOD_GET = 'GET';
	const REQUEST_METHOD_POST = 'POST';
        
        /**
         * Protocols
         */
	const PROTOCAL_HTTP = 'http';
        const PROTOCAL_HTTPS = 'https';
        
        /**
         * HTTP protocol version
         */
        const CURL_HTTP_VERSION_NONE = CURL_HTTP_VERSION_NONE;
        const CURL_HTTP_VERSION_1_0 = CURL_HTTP_VERSION_1_0;
        const CURL_HTTP_VERSION_1_1 = CURL_HTTP_VERSION_1_1;
        const CURL_HTTP_VERSION_2_0 = CURL_HTTP_VERSION_2_0;        
        const CURL_HTTP_VERSION_2TLS = CURL_HTTP_VERSION_2TLS;
        const CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE = CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE;         
}
