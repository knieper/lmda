<?php

/**
 * setup.php
 *
 * SquirrelMail Set User Data Plugin
 * Version 1.0
 *
 * Copyright (c) 2009 Ian Goldstein <ian@novoops.com>
 * Licensed under the GNU GPL. For full terms see the file COPYING
 *
 * @package plugins
 * @subpackage set_user_data
 *
 */ 



//error_reporting(E_ALL);
//ini_set('display_errors', '1'); 

 

// Initialize plugin
//
function squirrelmail_plugin_init_set_user_data() {
    global $squirrelmail_plugin_hooks;

    $squirrelmail_plugin_hooks['login_before']['set_user_data']=
        'set_user_data_login';
    $squirrelmail_plugin_hooks['logout']['set_user_data'] =
        'set_user_data_logout';
    $squirrelmail_plugin_hooks['loading_prefs']['set_user_data'] =
        'set_user_data_update';
    $squirrelmail_plugin_hooks['configtest']['set_user_data'] =
        'set_user_data_check_config';        
}
   



function set_user_data_login() {
    include_once(SM_PATH . 'plugins/set_user_data/functions.php');
    userdata_login();
}




// Called after the user logouts
//
function set_user_data_logout() {
    include_once(SM_PATH . 'plugins/set_user_data/functions.php');
    userdata_logout();
}




// Called after a successful login; sets user data if necessary
//
function set_user_data_update() {
    include_once(SM_PATH . 'plugins/set_user_data/functions.php');
    userdata_update();
}




// Validate that this plugin is configured correctly
//
function set_user_data_check_config() {
    include_once(SM_PATH . 'plugins/set_user_data/functions.php');
    return userdata_check_config();
}




// Returns info about this plugin
//
function set_user_data_info() {
    return array(
        'english_name' => 'Set User Data',
        'authors' => array(
            'Ian Goldstein' => array(
                'email' => 'ian@novoops.com'
                )
            ),
        //'external_proejct_uri' => 'http://'
        'version' => '1.0',
        'required_sm_version' => '1.4.0',     // only tested with 1.4.19 so far!
        'requires_configuration' => 1,
        'requires_source_patch' => 0,
        'summary' => 'This plugin updates a user\'s full name and email address(es) from one or more external source',
        'details' => 'This plugin updates a user\'s full name and email address(es) from one or more external source, allowing users to start sending email without having to first setup these values.'
    );
}



// Returns version info for this plugin
//
function set_user_data_version() {
    $info = set_user_data_info();
    return $info['version'];
}



