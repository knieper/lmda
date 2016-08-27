<?php

/*
 * passwd.php
 * Data Handler for passwd files
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
 
 
 
 

$GLOBALS['userdata_defaults_for_passwd'] = array(
    'posix' => 0,
    'passwd' => '/etc/passwd',
    'group' => '/etc/group'
);



// Retreive user data from the passwd file
//
//   when $user is NULL...
//     cannot search by email, so always returns false
//
//   otherwise...
//     searches by username, retrieves full name
//     email address could be derived by rules if necessary
//
//   returns true if data found, false if not
//
function userdata_from_passwd($key, &$user, &$email, &$name, $settings) {

    if ($user === NULL) {
        return false;
    }

    $found = false;
    
    // if set and avail, use posix functions to retreive user's name
    if ($settings['posix'] && function_exists('posix_getpwnam')) {   
        if (($data = posix_getpwnam($key)) !== false) {
            $found = true;
            $name = strtok($data['gecos'], ',');
            userdata_get_groups($settings['group'], $key, $data['gid']);
        }
    }

    // otherwise use direct file access
    else {
        $file = $settings['passwd'];
        if (is_file($file) && ($h = @fopen($file, 'rb'))) {
        
            while (!$found && !feof($h)) {
        
                // read next line, skipping empty and comment lines
                if (($line = fgets($h, 4096)) === false) break;
                if (($line = trim($line)) == '' || $line[0] == '#') {
                    continue;
                }
           
                // check for match
                $data = explode(':', $line);
                if ($data[0] == $key) {
                    $found = true;
                    $name = strtok($data[4], ',');
                    userdata_get_groups($settings['group'], $key, $data[3]);
                }            
            }
            @fclose($h);
        }
    }

    return $found;
    
} 




// get the list of groups that a user belongs to
//
//   called as userdata_get_groups() to return groups as an array
//   parameters are only passed internally by this handler for initialization
//
function userdata_get_groups($_file=NULL, $_user=NULL, $_gid=NULL) {
    static $groups = false;
    static $file, $user, $gid;
    
    // internal call (from userdata_from_passwd) to initialize this function
    if ($_file !== NULL) {
        $file = $_file;
        $user = $_user;
        $gid = $_gid;
        $groups = NULL;
        return;
    }
    
    // otherwise, return array containing groups for this user...
        
    // We should be able to optionally use posix functions here, but there
    // appears to be no simple mechanism to get a list of all groups a
    // particular user belongs to. We could call posix_getgrpid($gid) to
    // get data for the primary group, which would include it's name, but
    // we would still need a list of all groups. Therefore, it appears to
    // be easiest to just always use direct file access.

    if ($groups === NULL) {
        $groups = array();
    
        if (is_file($file) && ($h = @fopen($file, 'rb'))) {
    
            while (!feof($h)) {
                if (($line = fgets($h, 4096)) === false) {
                    break;
                }
                if (($line = trim($line)) == '' || $line[0] == '#') {
                    continue;
                }
                $data = explode(':', $line);
                if ($data[2] == $gid) {
                    $groups[] = $data[0];
                }
                elseif ($members = trim($data[3])) {
                    $members = explode(',', $members);
                    if (in_array($user, $members)) {
                        $groups[] = $data[0];
                    }
                }
            }
            @fclose($h);
        }
    }
    
    return $groups;

}


 
   

