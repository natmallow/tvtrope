<?php

/**
 * Loads files based on name-space
 * 
 * @author Nate Mallow <natdrip@gmail.com> 
 * 
 */

function lazyloader($className) {

    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';

    if ($lastNamespacePosition = strrpos($className, '\\')) {
        
        $namespace = strtolower(substr($className, 0, $lastNamespacePosition));
        $className = substr($className, $lastNamespacePosition + 1);
        $fileName = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    // allow for the use of name space alias
    if (!is_readable($fileName)) {

        echo ' COULD NOT LOAD ' . $fileName . '<br>'; //remove when done

        return;
    }

    //for debugging 
    //echo $fileName . ' --- > ' . __FILE__ . '<br>';

    require_once $fileName;
}

spl_autoload_register('lazyloader');
