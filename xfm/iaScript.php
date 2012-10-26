<?php

require_once(dirname(__file__).'/../../xfm/lib/Util/Script.php');

abstract class iaScript extends xScript {

    function setup_bootstrap() {
        // Instanciates the project specific bootstrap
        require_once(dirname(__file__).'/iaBootstrap.php');
        new iaBootstrap();
        // Sets a default 'script' username
        $_SERVER['HTTP_SHIB_PERSON_UID'] = 'script';
        $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION'] = 'localhost';
        $_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'] = 'local';
        xContext::$auth->set_from_aai();
    }
}