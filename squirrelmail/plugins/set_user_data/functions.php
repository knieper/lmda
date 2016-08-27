<?php

/**
 * functions.php
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
 
 

    
// Verify the plugin is configured correctly
//
//   returns true if any errors occured
//
function userdata_check_config() {
    if (($result = userdata_init()) !== true ||
        ($result = userdata_init_methods('data')) !== true ||
        ($result = userdata_init_methods('login')) !== true) {
    
        do_err("Set User Data plugin is $result", FALSE);
        return TRUE;
    }
    return FALSE;
}

   


// Initialize plugin
//
// returns boolean TRUE if successful, otherwise returns error message
//
function userdata_init() {
    static $init = false;
    global $userdata_config;
    global $userdata_methods, $userdata_num_methods;
    global $userlogin_methods, $userlogin_num_methods;

    if ($init) {
        return TRUE;
    }
           
    // get main configuration data 
    if (!is_file($file = SM_PATH . 'config/config_set_user_data.php') &&
        !is_file($file = SM_PATH . 'plugins/set_user_data/config.php')) {
        return 'missing main configuration file';
    }
    @include_once($file);
    
    // set any missing settings to default values
    $defaults = array(
        'datamode' => 1,      // normal data retrieval
        'delay' => 300,       // 5 minute delay between checks
        'alwaysupdate' => 1,  // always update names if changed
        'loginmode' => 0      // no login lookups
    );
    foreach ($defaults as $name => $default) {
        if (!isset($userdata_config[$name])) {
            $userdata_config[$name] = $default;
        }
    }
    
    // get list of data retrieval methods
    if (isset($userdata_config['datamethods'])) {
        $userdata_methods = $userdata_config['datamethods'];
        $userdata_num_methods = count($userdata_methods);
    }
    else {
        $userdata_methods = array();
        $userdata_num_methods = 0;
    }

    // get list of login lookup methods
    if (isset($userdata_config['loginmethods'])) {
        $userlogin_methods = $userdata_config['loginmethods'];
        $userlogin_num_methods = count($userlogin_methods);
    }
    else {
        $userlogin_methods = array();
        $userlogin_num_methods = 0;
    }
    
    $init = true;
    return TRUE;
}




// Initialize methods and handlers
//
// returns boolean TRUE if successful, otherwise returns error message
//

function userdata_init_methods($type) {

    static $init_data = 'data access';
    static $init_login = 'login lookup';
    
    $init = 'init_' . $type;
    if ($$init === true) {
        return TRUE;
    }

    $config = SM_PATH . 'config';
    $plugin = SM_PATH . 'plugins/set_user_data';

    $desc = $$init;
    $num =& $GLOBALS['user' . $type . '_num_methods'];
    $methods =& $GLOBALS['user' . $type . '_methods'];
    $method_settings =& $GLOBALS['user' . $type . '_settings'];

    // initialize each data access method
    for ($i = 0; $i < $num; ++$i) {
    
        $method = trim($methods[$i]);
        
        // determine any special settings for method
        if (substr($method, -2) === '?+') {
            $method = rtrim(substr($method, 0, -2));
            $continue = 1;
        }
        elseif (substr($method, -1) === '+') {
            $method = rtrim(substr($method, 0, -1));
            $continue = 2;
        }
        else {
            $continue = 0;
        }
        
        // get configured settings for method
        if ($method == '') {
            return "missing an expected $desc method";
        }
        if (!is_file($file = "$config/config_set_user_data_$method.php") &&
            !is_file($file = "$plugin/methods/$method.php")) {
            return "missing $desc method '$method' settings";
        }
        $methods[$i] = $method;
        $settings = array();
        @include_once($file);
        
        // load data handler
        if (!isset($settings['handler']) ||
            !($handler = $settings['handler']) ||
            !is_file($file = "$plugin/handlers/${handler}_handler.php")) {
            return "configured with an unknown data handler '$handler'";
        }   
        @include_once($file);
        
        // set any defaults and check for missing settings
        $defaults = $GLOBALS["userdata_defaults_for_$handler"];
        foreach ($defaults as $name => $default) {
            if (!isset($settings[$name])) {
                if ($default === NULL) {
                    return "missing '$name' setting for $desc method '$method'";
                }
                $settings[$name] = $default;
            }
        }
        if (!isset($settings['rules'])) {
            $settings['rules'] = 0;
        }
        if (!isset($settings['userrules'])) {
            $settings['userrules'] = 0;
        }
        
        // save settings
        $settings['continue'] = $continue;
        $method_settings[$method] = $settings;        
        
    }
    
    $methods[] = '';        // adds special '' handler at end for fixed rules
    ++$num;
    $$init = TRUE;          // indicates initilaization has been done
    
    return TRUE;
       
}

   


// Called when the user logs in
//
function userdata_login() {
    global $userdata_config, $login_username;
    //global $domain;
    
    // initialize plugin
    if (userdata_init() !== true) {
        return;
    }
    
    // get mode and exit if login lookup not enabled
    if (!($mode = $userdata_config['loginmode'])) {
        return;
    }
    
    // mode 1 = lookup if email address
    // mode 2 = lookup if email address; disallow otherwise
    // mode 3 = lookup all values
    if ($mode != 3 && strpos($login_username, '@') === false) {
        if ($mode == 2) {
            $login_username = '';
        }
        return;
    }

    // initialize methods
    global $userlogin_methods, $userlogin_num_methods, $userlogin_settings;
    if (userdata_init_methods('login') !== true) {
        return;
    }
    
    // adjust login name as needed
    $username = trim($login_username);
    if ($GLOBALS['force_username_lowercase']) {
        $username = strtolower($username);
    }
    // process each configured data access method
    for ($i = 0; $i < $userlogin_num_methods; ++$i) {
    
        if ($method = $userlogin_methods[$i]) {
        
            // get method handler and settings
            $settings = $userlogin_settings[$method];
            $func = 'userdata_from_' . $settings['handler'];
            if (!function_exists($func)) {
                continue;
            }
        
            // lookup key is login name, modified by any key rules as needed
            $vars = array('key' => $username);       
            $key = userdata_apply_rules('loginkey', $settings, $vars, 'key');
            
            // call external data handler
            //
            //   must return true or false indicating if username was found
            //   if data found will be stored in $username (passed by ref)
            //
            $user = NULL;
            $email = $name = '';
            if (!$func($key, $user, $email, $name, $settings) || !$user) {
                continue;
            }
        }
        else {
            // fixed (non-method specific) rules applied at end
            $settings = $userdata_config;
            $key = '';
            $user = $username;
        }     
            
        // apply rules to results and update login name if user set
        $vars = array('user' => $user);
        if ($key) {
            $vars['key'] = $key;
        }
        if ($user = userdata_apply_rules('login', $settings, $vars, 'user')) {
            $login_username = $user;
            break;
        }
    }
}




// Called when the user logs out
//
function userdata_logout() {
    global $data_dir;

    // retrieve username and clear last check timestamp
    sqgetGlobalVar('username', $username);  
    if (isset($username) && !empty($username)) {
        setPref($data_dir, $username, 'userdata_check', 0);
    }
    
}



   
// Called after a successful login
//
//   checks if updating of user data is necessarey
//   updates user preferences as needed
//
function userdata_update() {
    global $data_dir, $userdata_config;

    // initialize plugin
    if (userdata_init() !== true) {
        return;
    }
    
    // get mode and exit if data retrieval not enabled
    if (!($mode = $userdata_config['datamode'])) {
        return;
    }

    // retrieve username
    sqgetGlobalVar('username', $username);
    if (!isset($username) || empty($username)) {
        return;
    }
   
    // check if should only be done once
    if ($mode > 1) {
    
        // skip if already set
        if (getPref($data_dir, $username, 'userdata_set', 0)) {
            return;
        }
        
        // skip if mode 3 and any identities exist
        if ($mode == 3) {
            userdata_get_identities($username, $identities, $num_identities);
            if ($identities || $num_identities) {
                setPref($data_dir, $username, 'userdata_set', 1);
                return;
            }
        }
    }

    // skip check if it has been done recently
    if ($last_check = getPref($data_dir, $username, 'userdata_check', 0)) {
        if (time() - $last_check < $userdata_config['delay']) {
            return;
        }
    }
    
    // initialize methods
    global $userdata_methods, $userdata_num_methods, $userdata_settings;
    if (userdata_init_methods('data') !== true) {
        return;
    }
     
    // set last check timestamp
    setPref($data_dir, $username, 'userdata_check', time());

    // setup data structures to hold the user's data
    $data_emails = array();
    $data_name = '';
    $main_email = '';

    // process each configured data access method
    for ($i = 0; $i < $userdata_num_methods; ++$i) {

        if ($method = $userdata_methods[$i]) {
        
            $settings = $userdata_settings[$method];
            $func = 'userdata_from_' . $settings['handler'];
            if (!function_exists($func)) {
                continue;
            }
        
            // lookup key is username, modified by any key rules as needed
            $vars = array('key' => $username);
            $key = userdata_apply_rules('datakey', $settings, $vars, 'key');        
            
            // call external data handler
            //
            //   must return true or false indicating if user was found
            //
            //   if data found will be stored in $email/$name (passed by ref)
            //   can return either email address, full name or both
            //   can return $email as array of values or a single value
            //   if $email is array, $name can be matching array or single value
            //   if $email is not array or not found, $name must be single value
            //
            $email = $name = '';
            if (!$func($key, $username, $email, $name, $settings)) {
                if ($settings['continue'] == 1) {    // 1 = only continue if found
                    break;
                }
                continue;
            }
        }
        else {
            $settings = $userdata_config;
            $email = $name = '';
        }
            
        
        // save data for later use, applying any data rules as needed
        $vars = array('user' => $username);
        if ($key) {
            $vars['key'] = $key;
        }
        $get = array('email', 'name');
        
        if ($email) {
        
            // email address(es) returned from handler...
        
            // always treat email as multiples
            if (!is_array($email)) {
                $email = array($email);
            }
            
            
            // process each returned email addresss
            for ($j = 0, $k = count($email); $j < $k; ++$j) {
            
                // skip empty emails; apply rules to others
                if (!($vars['email'] = $email[$j])) {
                    continue;
                }
                $vars['name'] = is_array($name) ? $name[$j] : $name;
                $vars['mainemail'] = $main_email;
                list($email1, $name1) = userdata_apply_rules('data', $settings, $vars, $get);

                // store new emails and/or update name if missing from existing one
                if ($email1) {
                    if (!$main_email) {
                        $main_email = $email;
                    }        
                    if (!isset($data_emails[$email1]) || ($name1 && !$data_emails[$email1])) {
                        $data_emails[$email1] = $name1;
                    }
                }
                
                // if single name returned, use as default name (if not set)
                if ($name1 && !$data_name && !is_array($name)) {
                    $data_name = $name1;
                }
            }
        }
        else {
        
            // no email addresses were returned from handler...
            
            // apply rules
            $vars['name'] = $name;
            $vars['email'] = '';
            $vars['mainemail'] = $main_email;
            list($email, $name) = userdata_apply_rules('data', $settings, $vars, $get);

            // use name as default name (if not set)
            if ($name && !$data_name) {
                $data_name = $name;
            }
            
            // if email was derived, save it
            if ($email) {
                if (!$main_email) {
                    $main_email = $email;
                }
                $data_emails[$email] = $name;
            }
        }
        
        if (!$settings['continue']) {
            break;
        }
    }

    // if no email addresses found, cannot do any updating
    if (!$data_emails) {
        return;
    }

    // retreive all existing identities
    if (!isset($identities)) {
        userdata_get_identities($username, $identities, $num_identities);
    }
    
    // compare all email addresses to current identities
    // add or update user's data as needed
    $update = $userdata_config['alwaysupdate'];
    $prevnames = getPref($data_dir, $username, 'userdata_names', '');
    $prevnames = $prevnames ? unserialize($prevnames) : array();
    
    foreach ($data_emails as $email => $name) {
    
        // use default name if a specific name not set for this email
        if (!$name) {
            $name = $data_name;
        }

        // if email address not in identities, add as new identity
        if (!isset($identities[$c = strtolower($email)])) {
        
            $suffix = $num_identities ? $num_identities : '';
            setPref($data_dir, $username, 'email_address' . $suffix, $email);
            setPref($data_dir, $username, 'full_name' . $suffix, $name);
            $identities[$c] = array($num_identities, $name);
            ++$num_identities;
            
        }
            
        // if email address in identities, update full name as needed
        elseif (($oldname = $identities[$c][1]) != $name) {
            if ($update || !$oldname || $oldname == $prevnames[$c]) {
        
                if (!($suffix = $identities[$c][0])) {
                    $suffix = '';
                }
                setPref($data_dir, $username, 'full_name' . $suffix, $name);
                $identities[$c][1] = $name;
                
            }            
        }
    }
    
    // update number of identities (if changed)
    if ($num_identities != getPref($data_dir, $username, 'identities')) {
        setPref($data_dir, $username, 'identities', $num_identities);
    }

    // indicate user data has been set and save current name/email data
    setPref($data_dir, $username, 'userdata_set', 1);
    setPref($data_dir, $username, 'userdata_names', serialize($data_emails));
                
}




// Called to process any rules
//
function userdata_apply_rules($type, $settings, $vars, $returns) {

    // process all rules
    $num_rules = isset($settings[$type . 'rules']) ? $settings[$type . 'rules'] : 0;
    for ($rule_pos = 1; $rule_pos <= $num_rules; ++$rule_pos) {

        // parse space-delimited rule into commands (skip if empty)
        if (!isset($settings[$rule = $type . 'rule' . $rule_pos]) ||
            ($rule = trim($settings[$rule])) === '') {
            continue;
        }
        $cmds = explode(' ', $rule);
        $num_cmds = count($cmds);
            
        // check if this rule should continue when finished
        if (($continue = $cmds[$num_cmds - 1] == '+') && !(--$num_cmds)) {
            continue;
        }
            
        // process each command
        for ($cmd_pos = 0; $cmd_pos < $num_cmds; ++$cmd_pos) {
            
            // skip empty commands (i.e. multiple adjacent spaces in rule)
            if (($cmd = $cmds[$cmd_pos]) === '') {
                continue;
            }
                
            // split command into variable, operator and value
            if (($i = strpos($cmd, '=')) === false) {
                continue 2;
            }
            if (($val = substr($cmd, $i + 1)) === false) {
                $val = '';
            }
            if ($i && strspn($cmd[$i - 1], '?!~^')) {
                $op = $cmd[--$i] . '=';
                if ($op == '~=' && $i && $cmd[$i - 1] == '!') {
                    $op = '!~=';
                    --$i;
                }
            }
            else {
                $op = '=';
            }
            $var = strtolower(substr($cmd, 0, $i));

            // get variable
            //    check that var is valid
            //    determine/apply any modifier
            //    set var_val to current value
            //    set var_new to NULL, or false if cannot be modifed
            $modifier = userdata_get_modifier($var);
            if (!userdata_is_var($var)) {
                continue 2;
            }
            if ($var == 'group') {
                if ($modifier || ($op != '?=' && $op != '!=')) {
                    continue 2;
                }
                if (isset($groups)) {
                    $var_val = $groups;
                }
                elseif (function_exists('userdata_get_groups') &&
                    ($var_val = userdata_get_groups()) !== false) {
                    $groups = $var_val;
                }
                else {
                    $var_val = array();
                }
            }
            else {
                $var_val = isset($vars[$var]) ? $vars[$var] : '';
                if ($modifier) {
                    $var_saved = userdata_modify($modifier, $var_val);
                }
            }
            $var_new = NULL;
                
            // resolve any insertion vars in value
            userdata_insert_vars($val, $vars);
              
            // process operator
            switch ($op) {
                
                // basic conditionals
                case '?=':
                case '!=':
                    if ($var == 'group') {
                        $cond = in_array($val, $var_val);
                    }
                    else {
                        $cond = (bool) ($val == $var_val);
                    }
                    if ($op == '!=') {
                        $cond = !$cond;
                    }
                    if ($cond) {
                        break;
                    }
                    continue 3;
                        
                // regular expression conditional
                case '~=':
                case '!~=':
                    $cond = preg_match($val, $var_val, $matches);
                    if ($op[0] == '!') {
                        $cond = !$cond;
                    }
                    if ($cond) {
                        foreach ($matches as $i => $c) {
                            if (is_int($i)) {
                                $vars[$i] = $c;
                            }
                        }
                        break;
                    }
                    continue 3;
                    
                    // simple assignment
                case '=':
                    $var_new = $val;
                    break;
                            
                // insert character assignment
                case '^=':
                    if (($size = strlen($val)) < 2) {
                        continue 3;
                    }
                    if ($size == 2) {
                        $pos = 1;
                    }
                    else {
                        $pos = userdata_get_int($val, 2);
                        if ($pos === false) {
                            continue 3;
                        }
                    }
                    $chars = array();
                    $i = 0;
                    $ch = $val[1];
                    while (($i = strpos($var_val, $ch, $i)) !== false) {
                        $chars[] = $i++;
                    }
                    if (!($size = count($chars))) {
                        break;
                    }
                    $ch = $val[0];
                    if ($pos == 0) {
                        $var_new = substr($var_val, 0, $chars[0]) . $ch;
                        for ($i = 1; $i < $size; ++$i) {
                            $pos = $chars[$i - 1] + 1;
                            $var_new .= substr($var_val, $pos, $chars[$i] - $pos) . $ch;
                        }
                        $pos = $chars[$size - 1] + 1;
                        if ($pos < strlen($var_val)) {
                            $var_new .= substr($var_val, $pos);
                        }
                    }
                    else {                                               
                        if ($pos < 0) $pos += $size + 1;
                        if ($pos > 0 && --$pos < $size) {
                            $pos = $chars[$pos];
                            $var_new = substr($var_val, 0, $pos) . $ch . substr($var_val, $pos + 1);
                        }
                    }
                    break;                            
                    
            } // end of switch ($op)
                
            // assign new value if modified
            if ($var_new !== NULL) {
                if ($modifier && $var_saved) {
                    userdata_modify($modifier, $var_new, $var_saved);
                }
                $vars[$var] = $var_new;
            }
                    
        } // end of $cmd_pos loop
            
        if (!$continue) {
            break;
        }
            
    } // end of $rule_pos loop
        
    // return requested values
    if (is_array($returns)) {
        $vals = array();
        for ($i = 0, $size = count($returns); $i < $size; ++$i) {
            $vals[] = $vars[$returns[$i]];
        }
        return $vals;
    }
    
    return $vars[$returns];

}




//--------------------------------------
//
// Support Functions
//
//--------------------------------------



// Build a list of existing identities
//
function userdata_get_identities($username, &$list, &$num) {
    global $data_dir;

    $list = array();        

    // check for email address and name
    $email = getPref($data_dir, $username, 'email_address');
    if (!empty($email)) {
        $list[strtolower($email)] =
            array(0, getPref($data_dir, $username, 'full_name'));
    }

    // determine number of total identities
    $num = getPref($data_dir, $username, 'identities');
    if (empty($num)) {
        $num = $list ? 1 : 0;
    }

    // check for additional email addresses and names
    for ($i = 1; $i < $num; ++$i) {
        $email = getPref($data_dir, $username, 'email_address' . $i);
        if (!empty($email)) {
            $list[strtolower($email)] =
                array($i, getPref($data_dir, $username, 'full_name' . $i));
        }
    }    

}




// Return a method setting value or a default value
//
function userdata_setting($settings, $name, $default) {
    return isset($settings[$name]) ? $settings[$name] : $default;
}




// Verify a variable name is valid
//
function userdata_is_var($val) {
    if ($val == '') {
        return false;
    }
    // should we allow underscore char?
    if (function_exists('ctype_digit')) {
        return !ctype_digit($val[0]) && ctype_alnum($val);
    }
    if (strspn($val[0], '1234567890')) {
        return false;
    }
    return strspn(strtolower($val),
                  'abcdefghijklmnopqrstuvwxyz1234567890') == strlen($val);
}




// Extract an integer from a text string
//
function userdata_get_int($val, $pos) {
    if ($pos < strlen($val)) {
        if (($neg = $val[$pos] == '-') && ++$pos == strlen($val)) {
            return false;
        }
        $val = substr($val, $pos);
        if (strspn($val, '0123456789') == strlen($val)) {
            $num = (int) $val;
            return $neg ? -$num : $num;
        }
    }
    return false;   
}




// Insert variables into a text value 
//
function userdata_insert_vars(&$val, $vars) {
    $start = 0;
    
    // find next '{' character
    while (($start = strpos($val, '{', $start)) !== false) {
    
        // replace '{{' with literal '{'
        if ($start + 1 < strlen($val) && $val[$start + 1] == '{') {
            $val = substr($val, 0, $start) . substr($val, ++$start);
            continue;
        }
        
        // find next '}' character        
        if (($end = strpos($val, '}', $start)) === false) {
            break;
        }
        
        // extract variable name
        $var = strtolower(substr($val, $start + 1, $end - $start - 1));
        
        // replace '{}' with space
        if ($var == '') {
            $var = ' ';
        }
            
        // replace var with corresponding value; treat unknowns as literals
        else {
            $modifier = userdata_get_modifier($var);
            if (!isset($vars[$var])) {
                $start = $end;
                continue;
            }
            $var = $vars[$var];
            if ($modifier) {
                userdata_modify($modifier, $var);
            }
        }
        
        $val = substr($val, 0, $start) . $var . substr($val, $end + 1);
        $start += strlen($var);
    }

}




// Extract modifier from variable name
//
function userdata_get_modifier(&$var) {
    if (strlen($var) > 2 && $var[1] == ':' &&
        strspn($modifier = $var[0], 'ndtlui')) {
        
        $var = substr($var, 2);
        return $modifier;
    }
    return '';
}




// Apply modifier or restored saved portion after assignment operation
//
function userdata_modify($modifier, &$value, $saved=NULL) {

    if ($saved != NULL) {
        switch ($modifier) {
            case 'n':
                $value .= $saved;
                break; 
                
            case 'd':
            case 't':
                $c = $modifier == 'd' ? '@' : '.';
                if ($value) {
                    if ($saved == '' || substr($saved, -1) != $c) {
                        $saved .= $c;
                    }
                    $value = $saved . $value;
                }
                else {
                    $value = $saved && substr($saved, -1) == $c ? substr($saved, 0, -1) : $saved;
                }
                break;
        }
        return;
    }

    $saved = '';
    switch ($modifier) {
    
        // name (i.e. portion before @)
        case 'n':
            if (($i = strpos($value, '@')) !== false) {
                $saved = substr($value, $i);
                $value = substr($value, 0, $i);
            }
            break;
            
        // domain (i.e. portion after @)
        case 'd':
            if (($i = strpos($value, '@')) === false) {
                $saved = $value;
                $value = '';
            }
            else {
                $saved = substr($value, 0, ++$i);
                $value = (string) substr($value, $i);
            }
            break;
            
        // tld (portion after last '.')
        case 't':
            if (($i = strrpos($value, '.')) === false) {
                $saved = $value;
                $value = '';
            }
            else {
                $saved = substr($value, 0, ++$i);
                $value = (string) substr($value, $i);
            }
            break;
            
        // lowercase            
        case 'l':
            $value = strtolower($value);
            break;
            
        // uppercase
        case 'u':
            $value = strtoupper($value);
            break;

        // init caps            
        case 'i':
            if ($value !== '') {
                $value = strtolower($value);
                $value[0] = strtoupper($value[0]);
            }
            break;
            
    }
   
    return $saved;
}



//--------------------------------------
//
// Debugging Functions
// (remove when finished)
//
//--------------------------------------


function userdata_debug($text) {
    $h = @fopen('/tmp/get-email-name', 'a');
    @fwrite($h, date('Y-m-d H:i:s ') . $text . "\n");
    @fclose($h);
}

   


