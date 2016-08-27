<?php

/*
 * text.php
 * Data Handler for text files
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
 
 

 

$GLOBALS['userdata_defaults_for_text'] = array(
    'file' => '',
    'comments' => 0,
    'linecontinue' => 0,
    'delimiter' => ',',
    'userpos' => 1,
    'namepos' => 2,
    'emailpos' => 3,
    'mode' => 0,
    'separator' => ' '
);

 


// Retreive user data from a text file
//
//   when $user is NULL...
//     searches by email, retrieves username
//
//   otherwise
//     searches by username, retrieves email address(es) and full name(s)
//     can return a single email or multiple values as an array
//     if multiple, a matching array of names or a single value can be set
//
//   return true if data found, false if not
//
function userdata_from_text($key, &$user, &$email, &$name, $settings) {
    
    if (!is_file($file = $settings['file']) || !($h = @fopen($file, 'rb'))) {
        return false;
    }

    $found = false;
    if ($user === NULL) {
        $keypos = $settings['emailpos'];
        $userpos = $settings['userpos'];
        $mode = 0;
    }
    else {
        $keypos = $settings['userpos'];
        $namepos = $settings['namepos'];
        $emailpos = $settings['emailpos'];
        if (($mode = $settings['mode']) == 2) {
            $email_list = array();
            $name_list = array();
        }
    }
    if (!$keypos) {
        return false;
    }
    $delimiter = $settings['delimiter'];
    if ($notabs = ($delimiter == 'space')) {
        $delimiter = ' ';
    }
    $comments = $settings['comments'];
    $linecont = $settings['linecontinue'];
    
    $line = '';
    while ($line !== false) {
    
        // read next line
        if (feof($h) || ($nextline = fgets($h, 4096)) === false) {
            $nextline = false;
        }
        
        // skip empty lines and comment lines
        if (($c = ltrim($line)) == '' || ($comments && $c[0] == '#')) {
            $line = $nextline;
            continue;
        }
        
        // append continuation lines
        if ($linecont && ($c = ltrim($nextline)) != '' && $c != $nextline) {
            $line .= $c;
            continue;
        }        
           
        // parse line data
        if ($notabs) {
            $line = str_replace("\t", ' ', $line);
        }
        $data = explode($delimiter, $line);
        $size = count($data);
        $line = $nextline;
        
        // check for key match
        if ($keypos <= $size && trim($data[$keypos - 1]) == $key) {
            
            // extract data if found
            $found = true;
            if ($user === NULL) {
                if ($userpos && $userpos <= $size) {
                    $user = trim($data[$userpos - 1]);
                }
            }
            else {
                if ($namepos && $namepos <= $size) {
                    $name = trim($data[$namepos - 1]);
                }
                if ($emailpos && $emailpos <= $size) {
                     $email = trim($data[$emailpos - 1]);
                }
            
                // mode 2 allows for multiple records per user
                if ($mode == 2) {
                    $email_list[] = $email;
                    $name_list[] = $name;
                    continue;
                }

                // mode 1 allows for multiple email addresses in email field
                if ($mode == 1 && $email) {
                    $sep = $settings['separator'];
                    if ($sep == 'space') {
                        $email = str_replace("\t", ' ', $email);
                        $sep = ' ';
                    }
                    if (strpos($email, $sep) !== false) {
                        $email = explode($sep, $email);
                    }
                }
            }
            
            break;

        }            
    }
    @fclose($h);
    
    if ($mode == 2 && count($email_list) > 1) {
        $email = $email_list;
        $name = $name_list;
    }   

    return $found;
    
}



