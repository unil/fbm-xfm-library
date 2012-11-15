<?php

/**
 * Project specific Auth.
 * Uses Shibboleth information to automatically authenticate the user.
 * @package iafbm
 */
class iaAuth extends xAuth {

    protected $role_separator = ';';

    function __construct() {
        parent::__construct();
    }

    function set_from_aai() {
        // Retrives 'username' and 'roles' data from Shibboleth
        $authenticated = isset(
            $_SERVER['HTTP_SHIB_PERSON_UID'],
            $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION'],
            $_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF']
        );
        $username = $authenticated ? implode(
            '@',
            array(
                $_SERVER['HTTP_SHIB_PERSON_UID'],
                $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION']
            )
        ) : 'guest';
        $roles = @$_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'];
        // Development default values (!)
        $apply_development_default_auth =
            xContext::$profile == 'development' &&
            !$authenticated
        ;
        if ($apply_development_default_auth) {
            $username = @xContext::$config->dev->auth->username;
            $roles = @xContext::$config->dev->auth->roles;
        }
        // Prevents unauthenticated access
        if (!$username || !$roles) {
            throw new xException('You must be authenticated to continue', 403);
        }
        // Determines wether 'roles' have changed since last request
        $roles_have_changed = (implode(';', $this->roles()) != $roles);
        // Sets auth information
        $this->set($username, $roles, $this->info());
    }
}