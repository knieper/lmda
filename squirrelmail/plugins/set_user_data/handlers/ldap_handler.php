<?php

/*
 * ldap.php
 * Data Handler for LDAP Server Access
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
 


$GLOBALS['userdata_defaults_for_ldap'] = array(
    'main' => 0,
    'host' => '',
    'port' => 389,
    'base' => '',
    'charset' => 'utf-8',
    'anonymousbind' => 0,
    'rdnbind' => '',
    'user' => '',
    'name' => '',
    'email' => '',
    'aliases' => '',
    'aliasprefix' => ''
);
 


// Retreive user data from an LDAP server
//
//   when $user is NULL...
//     searches by email, retrieves username
//
//   otherwise...
//     searches by username, retrieves email address(es) and full name(s)
//     can return a single email or multiple values as an array
//     if multiple, a matching array of names or a single value can be set
//
//   returns true if data found, false if not
//
function userdata_from_dba($key, &$user, &$email, &$name, $settings) {

    // when 'main is set, use the LDAP address book from main SM config file
    if ($settings['main']) {
        $server = $GLOBALS['ldap_server'][0];
    }
    // otherwise, use the settings as specified in this method's config file
    else {
        $server = $settings;
    }
        
    $found = false;
    
    // connect to ldap server
    if (!($host = $server['host']) ||
        ($link = ldap_connect($host, $server['port'])) === false) {
        return false;
    }
    
    // bind to ldap server
    if ($settings['anonymousbind']) {
        $result = ldap_bind($link);
    }
    else {
        $rdn = $settings['rdnbind'];       
        $vars = array('key' => $key, 'user' => $user);
        userdata_insert_vars($rdn, $vars);
        
        // get user password
        sqgetGlobalVar('key', $password, SQ_COOKIE);
        sqgetGlobalVar('onetimepad', $onetimepad);
        $plaintext = OneTimePadDecrypt($password, $onetimepad);
        
        $result = ldap_bind($link, $rdn, $plaintext);
    }

    // search for user
    if ($result) {
    
        $base = $server['base'];
        $charset = $server['charset'];
        
        $user_field = $settings['user'];
        $email_field = $settings['email'];
        $name_field = $settings['name'];
        $alias_field = $settings['aliases'];
        $prefix = strtolower($settings['aliasprefix']);
        
        if (!$base || !$user_field || !$email_field || !$name_field) {
            $results = false;
        }
        elseif ($user === NULL) {
            $filter = $email_field . '=' . $key;
            $attributes = array($user_field);
            $results = ldap_search($link, $base, $filter, $attributes);
        }
        else {
            $filter = $user_field . '=' . $key;
            $attributes = array($name_field, $email_field, $alias_field);
            $results = ldap_search($link, $base, $filter, $attributes);
        }
        
        if ($results !== false) {
            $data = ldap_get_entries($link, $results);
            if ($data['count'] >= 1) {
            
                if ($user === NULL) {
                    $user = $data[0][strtolower($user_field)][0];
                    $user = userdata_charset_decode($user, $charset);
                }
                
                else {
            
                    $name = $data[0][strtolower($name_field)][0];
                    $name = userdata_charset_decode($name, $charset);

                    $addrs = $data[0][strtolower($email_field)];            
                    for ($i = 0; isset($addrs[$i]); ++$i) {
                        $email[] = userdata_charset_decode($addrs[$i], $charset);
                    }
                 
                    $addrs = $data[0][strtolower($alias_field)];
                    $len = strlen($prefix);
                    for ($i = 0; isset($addrs[$i]); ++$i) {
                        $alias = $addrs[$i];
                        if ($len) {
                            if (strtolower(substr($alias, 0, $len)) === $prefix) {
                                $alias = ltrim($substr($alias, $len));
                            }
                            else {
                                $alias = '';
                            }
                        }
                        if (!empty($alias)) {
                            $email[] = userdata_charset_decode($alias, $charset);
                        }
                    }
                }
            }
        }
    }

    // disconnect from ldap server
    ldap_close($link);

    return $found;
    
} 


       

// Decode from charset used by LDAP server to iso8859-1
// This code based on code from Squirrelmain functions/abook_ldap_server.php
//
function userdata_charset_decode($text, $charset) {
    if ($charset == 'utf-8') {
        if (function_exists('utf8_decode')) {
            return utf8_decode($text);
        }
    }
    return $text;
}




   

