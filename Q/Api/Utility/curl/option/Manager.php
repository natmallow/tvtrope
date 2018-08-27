<?php

namespace Q\Api\Utility\Curl\Option;

/**
 * 
 * TODO: Will probably deprecate this  
 * 
 */
class Manager {

    /**
     * This will cover all options except url
     * @var $aCurlOptions type array
     */
    protected static $aCurlOptions = array();
    private static $instance = null;

    /**
     * @static var null $instance call to get singleton
     * @return \Q\Api\Utility\Curl\Option
     */
    public static function getInstance() {

        if (null === static::$instance) {
            static::$instance = new Manager();
        }
        return static::$instance;
    }

    /**
     * 
     * @return array
     */
    public function getACurlOptions() {
        return self::$aCurlOptions;
    }

    /**
     * 
     * @param array $aCurlOptions
     */
    public function addACurlOptions(array $aCurlOptions) {
        //know array_replace perserves the key values
        self::$aCurlOptions = array_replace($aCurlOptions, self::$aCurlOptions);
    }

    /**
     * Resets options
     * otherwise set it to False
     */
    public static function setACurlOptions(array $aCurlOptions = null) {
		if (!is_null($aCurlOptions)) {
			self::$aCurlOptions = $aCurlOptions;
		}
	}

    /**
     * Protected constructor to prevent creating a new instance of the Singleton
     * via the new operator from outside of this class.
     */
    protected function __construct() {
        
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * Singleton instance.
     *
     * @return void
     */
    private function __clone() {
        
    }

    /**
     * Private unserialize method to prevent unserializing of the Singleton
     * instance.
     *
     * @return void
     */
    private function __wakeup() {
        
    }

}
