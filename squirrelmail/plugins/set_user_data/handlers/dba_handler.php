<?php

/*
 * dba.php
 * Data Handler for DBA Access
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
 
 
 


 
$GLOBALS['userdata_defaults_for_dba'] = array(
    'file' => NULL,
    'type' => NULL,
    'separator' => ',',
    'userpos' => 0,
    'namepos' => 1,
    'emailpos' => 2
);



// Retreive user data from DBM-like database
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
//   Datbase file should be created using create_db.php on the command line
//   Use an input file with lines in the format:
//       user,name,email
//
function userdata_from_dba($key, &$user, &$email, &$name, $settings) {
      
    // access database
    $file = $settings['file'];
    $type = $settings['type'];
    if ($file === NULL || $type === NULL || !is_file($file)) {
        return false;
    }
    if (($h = dba_open($file, 'r', $type)) === false) {
        return false;
    }

    $found = false;
    
    // search for record 
    if (dba_exists($key, $h)) {
       
        // extract data
        $found = true;
        $data = explode($settings['separator'], dba_fetch($key, $h));
        $size = count($data);

        if ($user === NULL) {
            if (($userpos = $settings['userpos']) && $userpos <= $size) {
                $user = trim($data[$userpos - 1]);
            }
            
            // if userpos == 0, then it can we assume it is the key?
            // should we locate it as follows...
            //
            //$emailpos = $settings['emailpos'];
            // if ($emailpos && $k = dba_firstkey(h)) do {
            //    $data = explode($settings['separator'], dba_fetch($k, $h));
            //    $size = count($data);
            //    if ($emailpos <= $size && $data[$emailpos - 1] == $key) {
            //        $user = $k;
            //        break;
            //    }
            //} while ($k = dba_nextkey($h));
            
        }
        else{               
            if (($namepos = $settings['namepos']) && $namepos <= $size) {
                $name = trim($data[$namepos - 1]);
            }
            if (($emailpos = $settings['emailpos']) && $emailpos <= $size) {
                $email = trim($data[$emailpos - 1]);
            }
        }
        
        // possible future option...
        //    if mutiple records supported (i.e. cdb type), get add'l records
        //    need to test this with a cdb database...
        //
        //if ($settings['multi'] && $type == 'cdb' && $emailpos &&
        //    version_compare(PHP_VERSION, '4.3.0', '>=')) {
        //
        //    $i = 0;
        //    while (($data = dba_fetch($key, ++$i, $h)) !== false) {
        //        if ($i == 1) {
        //            $email = array($email);
        //            if ($namepos) {
        //                $name = array($name);
        //            }
        //        }
        //        $data = explode($settings['separator'], $data);
        //        $size = count($data);
        //        if ($emailpos && $emailpos <= $size) {
        //            $email[] = trim($data[$emailpos - 1]);
        //        }
        //        if ($namepos && $namepos <= $size) {
        //            $name[] = trim($data[$namepos - 1]);
        //        }
        //    }
        //}       
        
    }
    dba_close($h);

    return $found;
    
} 



