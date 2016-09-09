<?php

/*
 * postgresql.php
 * Data Handler for PostgreSQL Server
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
 
 
 


$GLOBALS['userdata_defaults_for_postgresql'] = array(
    'server' => NULL,
    'port' => NULL,
    'username' => NULL,
    'password' => NULL,
    'database' => NULL,
    'table' => NULL,
    'user' => NULL,
    'name' => '',
    'email' => '',
    'persistent' => 0
);
 


// Retreive user data from a PostgreSQL database
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
function userdata_from_postgresql($key, &$user, &$email, &$name, $settings) {

    // build PostgreSQL connection string
    $host = $settings['server'];
    $port = $settings['port'];
    $db_user = $settings['username'];
    $db_pass = $settings['password'];
    $database = $settings['database'];    
    if (!$host || !$port || !$db_user || !$db_pass || !$database) {
        return false;
    }    
    $connect = " host=$host port=$port user=$db_user password=$db_pass dbname=$database";
    
    // Connect to server
    $func = $settings['persistent'] ? 'pg_pconnect' : 'pg_connect';  
    if (($link = $func($connect)) === false) {
        return false;
    }

    $found = false;
    $key = addslashes($key);
    $table = $settings['table'];
    if ($user === NULL) {
        $key_field = $settings['email'];
        $user_field = $settings['user'];
        $fields = $user_field;
    }
    else {
        $key_field = $settings['user'];
        $email_field = $settings['email'];
        $name_field = $settings['name'];
        if ($email_field) {
            $fields = $email_field;
            if ($name_field) {
                $fields .= ', ' . $name_field
            }
        }
        else {
            $fields = $name_field;
        }  
    }
    
    if ($table && $key_field && $fields) {
    
        $query = "SELECT $fields FROM $table WHERE $key_field = '$key'";
    
        if (($result = pg_query($link, $query)) !== false) {
            if (($data = pg_fetch_array($result)) !== false) {
        
                $found = true;
                
                if ($user === NULL) {
                    $user = $data[$user_field];
                }
                else {
                    $email = $user_field ? $data[$email_field] : '';
                    $name = $name_field ? $data[$name_field] : '';
                }
            }
        }
    }
    
    if (!$settings['persistent']) {
        pg_close($link);
    }

    return $found;
    
} 



