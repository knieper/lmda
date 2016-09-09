<?php

/*
 * Configuration file for passwd data handler
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
 
 



// Set to 1 to use posix functions to access /etc/passwd data.
// Otherwise, set to 0 to use direct file access
//
$settings['posix'] = 1;



// If posix functions are not used, this indicates the location of the
// passwd and group files, which are normally /etc/passwd and /etc/group.
// They can be changed to access alternate files.
//
$settings['passwd'] = '/etc/passwd';
$settings['group'] = '/etc/group';



// Rules can be used to modify lookup values as well as results. In addition,
// if a value cannot be retrieved directly from an external source, it is
// possible to derive the value using rules. See the RULES document for
// complete details on rule syntax.
//
// There are 4 types of rules defined below. Some are used prior to a lookup
// to allow the key value used for the lookup to be modified. Others are
// used after the lookup to adjust the results.
//
// For each type of rule, the total number of rules must be set. If set to 0
// then that rule type will not be used. After the number of rules are set,
// each rule must be listed in consecutive order.


// Data Key rules are used to adjust the key value used to lookup user data.
// The key value is typically the username, but it can be adjusted as needed
// prior to the search using rules.
//
$settings['datakeyrules'] = 0;
//$settings['datakeyrule1'] = '';


// Data rules are used after the data has been located to derive and/or
// adjust the email address or full name.
//
// The following is an example set of rules...
//
//   $settings['datarules'] = 3;
//   $settings['datarule1'] = 'group?=users email={user} email^=@.-2';
//   $settings['datarule2'] = 'group?=admins email=admin@{user} name=Administrator';
//   $settings['datarule3'] = 'email={user}@domain.com';
//
//   which will do the following...
//
//     1. If the user is part of the 'users' group, the username will be used
//        as the email address with the second to last '.' character converted
//        to a '@' character. For example, for the user 'joe.example.com' the
//        email address will become 'joe@example.com'
//
//     2. If the user is part of the 'admins' group, then the email addresss
//        will use 'admin@' plus the username. For example, the email address
//        for the user 'example.com' would be 'admin@example.com'. In addition,
//        the full name will be set to 'Administrator'.
//
//     3. Otherwise, the user is assumed to be a Unix system user and the
//        email address will be set by appending a fixed domain to it. Thus,
//        the email for 'admin' would be 'admin@domain.com'.
//
$settings['datarules'] = 0;
//$settings['datarule1'] = '';


// Login key rules are used to adjust the key value used to lookup logins by
// email when login lookups are enabled. The key value is typically the login
// name, but it can be adjusted as needed prior to the search using rules.
//
$settings['loginkeyrules'] = 0;
//$settings['loginkeyrule1'] = '';


// Login rules are used after the login lookup has been done to further adjust
// the login name as needed.
//
$settings['loginrules'] = 0;
//$settings['loginrule1'] = '';



// THE FOLLOWING VALUE SHOULD ***NOT*** BE CHANGED
//
// This setting indicates the actual data handler to be used in
// order to retrieve the data. If this config file has been
// renamed or copied to an alternate file, the following setting
// must still remain set as-is in order to access the proper
// file in the set_user_data/handlers directory.
//
$settings['handler'] = 'passwd';



?>
