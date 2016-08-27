<?php

/*
 * Configuration file for Postfix virtual file handler
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
 
 




// Set this to the full path to the Postfix virtual text file
//
//$settings['file'] = '/etc/mail/virtusertable';
$settings['file'] = '/etc/mail/virtusertable';



// These settings are specific to the Postfix virtual file and should NOT
// be changed. See the text.php.sample file for more information on them.
//
$settings['comments'] = 1;
$settings['linecontinue'] = 1;
$settings['delimiter'] = 'space';
$settings['userpos'] = 2;
$settings['namepos'] = 0;
$settings['emailpos'] = 1;
$settings['mode'] = 2;



// Rules can be used to modify lookup values as well as results. In addition,
// if a value cannot be retrieved directly from an external source, it is
// possible to derive the value using rules. See the RULES document for
// complete details on rule syntax.
//
// There are 4 types of rules defined below. Some are used prior to a lookup
// to allow the key value used for the lookup to be modified. Other are
// used after the lookup to adjust the results.
//
// For each type of rule, the total number of rules must be set. If set to 0
// then that rule type will not be used. After the number of rules are set,
// each rule must be listed in consecutive order.
//
// See the passwd.php.sample file for an example set of rules.


// Data key rules are used to adjust the key value used to lookup user data.
// The key value is typically the username, but it can be adjusted as needed
// prior to the search using rules.
//
$settings['datakeyrules'] = 0;
//$settings['datakeyrule1'] = '';


// Data rules are used after the data has been located to derive and/or
// adjust the email address or full name.
//
// The following example set of rules will skip any email addresses which
// do not have a domain (i.e. not in the form name@domain.com), those in the
// form domain.com@domain.com, and those where the domain is not found at
// the end of the username (e.g. if user is name.domain.com, name@domain.net
// will be skipped).
//
// Alternatively, if other methods have previously set the main email address,
// the following rule would skip all email addresses which did not include
// the same domain as the main email:
//
//    $settings['datarule1'] = 'd:email!={d:mainemail} email=';
//
$settings['datarules'] = 3;
$settings['datarule1'] = 'd:email?= email=';
$settings['datarule2'] = 'n:email?={d:email} email=';
$settings['datarule3'] = 'user!~=/\b{d:email}$/ email=';


// Login key rules are used to adjust the key value used to lookup logins by
// email when login lookups are enabled. The key value is typically the login
// name, but can be adjusted as needed prior to the search using rules.
//
$settings['loginkeyrules'] = 0;
//$settings['loginkeyrule1'] = '';


// Login rules are used after the login lookup has been done to further adjust
// the login name as needed.
//
$settings['loginrules'] = 0;
$settings['loginrule1'] = 'user~=/,/ user=';



// THE FOLLOWING VALUE SHOULD ***NOT*** BE CHANGED
//
// This setting indicates the actual data handler to be used in
// order to retrieve the data. If this config file has been
// renamed or copied to an alternate file, the following setting
// must still remain set as-is in order to access the proper
// file in the set_user_data/handlers directory.
//
$settings['handler'] = 'text';



?>
