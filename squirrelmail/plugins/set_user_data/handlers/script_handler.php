<?php

/*
 * script.php
 * Data Handler for Script-based User Info
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
 
 
 
 

$GLOBALS['userdata_defaults_for_script'] = array(
    'script' => NULL,
    'parameters' => '{user}',
    'user' => 1,
    'name' => 2,
    'email' => 3,
    'mode' => 0
);



// Retreive user data via a script
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
function userdata_from_script($key, &$user, &$email, &$name, $settings) {
  
    if (!is_file($script = $settings['script'])) {
        return false;
    }
    
    $parameters = $settings['parameters'];
    userdata_insert_vars($parameters, array('key' => $key));

    // execute script and capture output
    exec("$script $parameters", $output, $status);

    // if an error code is returned or no data, then wasn't found
    if ($status ||!($rows = count($output))) {
        return false;
    }
    
    // when user is NULL, extract username from data
    if ($user === NULL) {
        $userrow = $settings['user'];
        if ($userrow < 0) {
            $userrow += $rows + 1;
        }
        if ($userrow < 0 || $userrow > $rows) {
            return false;
        }
        $user = $output[$userrow - 1];
        return true;        
    }
    
    // otherwise, extract email address(es) and/or name(s)...
    // first, determine row(s) where data is located
    $namerow = $settings['name'];
    if ($namerow < 0) {
        $namerow += $rows + 1;
    }
    if ($namerow < 0 || $namerow > $rows) {
        $namerow = 0;
    }
    $emailrow = $settings['email'];
    if ($emailrow < 0) {
        $emailrow += $rows + 1;
    }
    if ($emailrow < 0 || $emailrow > $rows) {
        $emailrow = 0;
    }
    if (!$namerow && !$emailrow) {
        return false;
    }

    // next, extract data    
    switch ($settings['mode']) {
    
        // multiple email address mode
        case 1:
            if ($namerow) {
                $name = $output[$namerow - 1];
            }
            if ($emailrow) {
                $lastrow = $namerow > $emailrow ? $namerow - 1 : $rows;
                $email = array();
                for (; $emailrow <= $lastrow; ++$emailrow) {
                    $emails[] = $output[$emailrow - 1];
                }
                if (count($email) == 1) {
                    $email = $email[0];
                }
            }
            break;
            
        // multiple name/email pairs
        case 2:
            if (!$emailrow) {
                return false;
            }
            for (; $emailrow <= $rows; $emailrow += 2) {
                $email[] = $output[$emailrow - 1];
                $name[] = $emailrow < $rows ? $output[$emailrow] : '';
            }
            if (count($email) == 1) {
                $email = $email[0];
                $name = $name[0];
            }
            break;
    
        // normal mode (full name and/or email address)
        default:
            if ($namerow) {
                $name = $output[$namerow - 1];
            }
            if ($emailrow) {
                $email = $output[$emailrow - 1];
            }
            break;
    }
    
    return true;

}




