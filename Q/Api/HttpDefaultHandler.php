<?php


namespace Q\Api;

use Q\Api\Utility\Curl\Option\CommonSettings as curlSettings;
use Q\Api\HttpBase;

class HttpDefaultHandler extends HttpBase{

    /**
     * Make curl call to retrieve the data
     * 
     * @throws \Exception
     * @return mixed
     */
    public function requestDataGeneric() {
        //trace 
        //echo '<br>--------------trace----------'.__FILE__.'---'.__LINE__.'<br>';
        $url = $this->getServiceUrl();
        $headers = $this->getUsedHeaders();
        
        //var_dump($headers);
        //echo '<br>--------------trace----------'.__FILE__.'---'.__LINE__.'<br>';
        $ch = curl_init($url);
        $http_method = strtoupper($this->sHttpRequestMethod);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);

        switch ($http_method) {
            case 'GET':
                $url = $url . $this->getAPramsUrlString();
                break;
            case 'POST':
                $data = $this->getAPramsJson();
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_POST, 1);
                break;
            default:
                throw new \Exception("Unsupported http method has been used. Supported http methods: GET, POST");
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $url);

        $port = $this->getUsedPort();
        if (!empty($port)) {
            curl_setopt($ch, CURLOPT_PORT, $port);
        }

        /**
         * doesn't do anything right here but you can set an add
         */
        //curlSettings\Manager::setACurlOptions();
        $this->addACurlOptions(curlSettings::recommendedDefault());

        curl_setopt_array($ch, $this->getACurlOptions());

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        
        # set http connection and fetch timeouts
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->iConTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->iTimeout);

        $result = curl_exec($ch);


        $iHttpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $sResponseFormat = trim(strtolower(curl_getinfo($ch, CURLINFO_CONTENT_TYPE)));

        $this->setSResponse($result);

        if (curl_errno($ch)) {
            $response = curl_getinfo($ch);
            $this->setSResponse($response);
            //print_r($response);
            # some logging

            throw new \Exception(curl_error($ch));
        }

        # close the curl connection
        curl_close($ch);
        return $result;
    }


    public function getServiceUrl() {
        # used this if port number must be in the url (e.g load balancer requirement)
        $port = $this->getUsedPort();
        #$port	= "";
        //$url = $this->sProtocol . "://" . $this->sHost . $port . HttpBase::URL_PATH_SEPERATOR . $this->sPath;
        $url = $this->sHost;// . $port . HttpBase::URL_PATH_SEPERATOR . $this->sPath;

        return $url;
    }

    /**
     * Retrieve the headers array as string array.
     * e.g
     * ["Content-type" => "json"] ===> [0 => "Content-type: json"]
     * @return multitype:string
     */
    public function getUsedHeaders() {
        $headers = array();
        if (is_array($this->aHeaders)) {
            foreach ($this->aHeaders as $k => $v) {
                $headers[] = $k . ": " . $v;
            }
        } else {
            throw new \Exception("getUsedHeaders: Headers must be in array format.");
        }

        return $headers;
    }

    /**
     * Retrieve the used port string for url.
     *
     * @return string port = 80, empty, or null will return empty string
     */
    public function getUsedPort() {
        return (($this->iPort == null || $this->iPort == 80 || (is_string($this->iPort) && empty($this->iPort) == "")) ? "" : ":" . $this->iPort);
    }

    /**
     * Takes the aPrams and makes them a string
     * @return type
     */
    public function getAPramsUrlString() {
        $params = "?";
        if (is_array($this->aParams)) {
            foreach ($this->aParams as $key => $value) {
                $params .= $key . "=" . $value . "&";
            }
        } else {
            $params .= $this->aParams;
        }
        return $params;
    }

    /**
     * Takes the aPrams and makes them a json string
     * @return type
     */
    public function getAPramsJson() {
        $data = $this->aParams;
        if (is_array($data) && count($data) > 0) {
            $data = json_encode($this->aParams);
        }
        return $data;
    }

    /**
     * Takes the aPrams and makes them an xml
     * @depends tmXml
     * @return type
     */
    public function getAPramsXml() {
        $xml = new SimpleXMLElement('<rootTag/>');
        to_xml($xml, $this->aParams);
        return $xml->asXML();
    }

    /**
     * @link  http://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml 
     * @param \Model\Api\SimpleXMLElement $object
     * @param array $data
     */
    private function toXml(SimpleXMLElement $object, array $data) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $new_object = $object->addChild($key);
                toXml($new_object, $value);
            } else {
                $object->addChild($key, $value);
            }
        }
    }

}
