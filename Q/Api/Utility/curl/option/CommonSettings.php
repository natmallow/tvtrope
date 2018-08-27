<?php

namespace Q\Api\Utility\Curl\Option;

/**
 * 
 */
class CommonSettings {

    /**
     * These are the required options for image upload
     * @var $sUrl type string
     * @var $sParams type string 
     */
    public static function recommendedDefault() {

        $options = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
        );

        return $options;
    }
    
    /**
     * Returns the post default curl options
     * @return array
     */
    public function post() {

        $options = array(
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => 1
        );

        return $options;
    }

    /**
     * Returns the get default curl options
     * @return array
     */
    public function get() {

        $options = array(
            CURLOPT_CUSTOMREQUEST => 'GET',
        );

        return $options;
    }

}
