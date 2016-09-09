<?php

/**
 * Main Configuration File
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
 
 

global $userdata_config;

  


// This value determines when data retrieval is done. Normally,
// the user data is checked (and updated when changed) at login
// and is also periodically checked for changes. Setting this
// value to 2 or 3 will result in data only being set once, and
// setting it to 0 will disable all data retrieval:
//
//    0 = disabled (full name and email addresses not retrieved)
//    1 = normal checking and updating (default)
//    2 = once it is set, no further checking/updating done
//    3 = once it is set, no further checking/updating done;
//        if any identities exist treated as if was set
//
$userdata_config['datamode'] = 1;



// This value controls how long to delay additional checks for
// changed data is delayed. This is to reduce unnecessary checks
// for user data. The value is in seconds. This value is only
// used if data mode (above) is set for normal checking.
//
$userdata_config['delay'] = 300;



// Set this value to 1 to allow this plugin to always update the user's
// full name when it has been changed by an external source. If you do
// not let your users edit their identities, you would want this value
// at 1 to always keep the full name updated.
//
// However, if users are allowed to edit their identities, or at least
// able to edit their full names, then this value should be set to 0. This
// will preserve user changes to the full name. The plugin will still
// update the full name when it changes, but only if it is missing or
// if it is still set to the same value as last set by the plugin.
//
$userdata_config['alwaysupdate'] = 0;



// Select the data access method(s) to be used to retreive user
// data. You may specify a single method or multiple methods.
//
// Currently available data access methods include:
//
//   text       - Plain text file
//   virtual    - postfix virtual user file
//   passwd     - /etc/passwd file
//   script     - external script
//   vpopmail   - vpopmail
//   ldap       - LDAP and Microsoft Active Directory
//   dba        - DBM-like database
//   mysql      - MySQL database
//   postgresql - PostgreSQL database
//
// If multiple methods are listed they will be used in the order
// specified, and data retrieval will normally stop upon the
// first successful match. This behavior can be changed as follows:
//
//   if a method ends with '+', searching will always continue with the
//   next method, whether or not the current method finds a match.
//
//   if a method ends with '?+', searching will only continue with the
//   next method if the current method does find a match.
//
// This ability to always or conditionally use additional methods gives
// you the flexibility to pull data from multiple sources. It could also
// be used to retrieve email address(es) and the full name from different
// sources, or to pull additional emails from a second source. For example,
// if you wanted to search the /etc/passwd file for a match and then search
// an additional text file for other email addresses, but only if a match
// was found in /etc/passwd, you would specify the methods as 'passwd+'
// and 'text'.
//
// You must create a setting file for each method (see the INSTALL doc
// for more information).
//
// Please note that each entry must be in quotes, and with multiple methods
// commas must be included after each value, except the last one.
//
$userdata_config['datamethods'] = array(
'passwd+', 'virtual',
);



// ADVANCED CONFIGURATION OPTION
//
// The previous configuration setting allows multiple data methods to be
// used. It will also allow a method to be used more than once with
// different settings, as long as each entry in the list is unique.
//
// For example, to use the 'text' data method with two configuration sets,
// 'text' and 'text2' could be added to the a list of methods to be used,
// as if they were completely separate methods. Both will require their
// own separate config file. After creating the config file for the 'text'
// data method (as detailed in INSTALL), it can then be copied and adjusted
// to create the config file for 'text2'.
//
//    $ cp config_text.php config_text2.php
//    $ vi config_text2.php
//
// Technically, this indicates to the plugin that there are two separate
// methods called 'text' and 'text2' which will be used as possible means
// to retrieve the data. However, the config file for each method includes
// a setting which indicates the actual handler code to be used, which in
// this case, for both will be 'text'.



// Fixed rules can be applied after the above defined methods have been
// used. These rules can be used to adjust and/or derive the email address
// and full name. See the RULES document for details on rule syntax.
//
// First the number of rules must be set. This should be 0 if there are
// no rules to be used, otherwise it holds the total number of rules. Next,
// each rule must be listed in consecutive order.
//
// See the passwd.php.sample file for an example set of rules.
//
$userdata_config['datarules'] = 0;
//$userdata_config['datarule1'] = '';



// This value determines if and when login lookups are done. If enabled
// (i.e. value is not 0), then the username used during the login process
// will be looked up using various methods to attempt to replace the login
// name with an alternative name. This can be useful to allow users to login
// with an email address, which will be converted to the actual username.
//
//    0 = no login lookups are done (default)
//    1 = lookup email addresses (i.e. login name contains '@' character)
//    2 = lookup email addresses and disallow non-email addresses
//    3 = lookup all login names, even if not an email address
//
$userdata_config['loginmode'] = 1;



// When login lookups are done, this sets the method(s) used to do these
// lookups. Please see the notes above regarding the data access method(s)
// for information about the format and values for this item. This setting
// is configured in the same manner as the data access method(s).
//
// Searching will always end once a username has been located, so if a '+'
// character is used at the end of a method in this list, it is ignored.
//
$userdata_config['loginmethods'] = array('virtual'
);


// Fixed rules can be applied after the above defined methods have been
// used. These rules can be used to adjust the login username. See the
// RULES document for details on rule syntax. For examples of rules, see
// the config_passwd.php.sample file.
//
// First the number of rules must be set. This should be 0 if there are
// no rules to be used, otherwise it holds the total number of rules.
//
// Next, each rule must be listed in the settings 'loginrule1',
// 'loginrule2', etc.
//
// For example, if you wanted to allow users to login using email
// addresses, but needed to convert the email address into a system
// username where a '.' was used instead of the '@' sign, you could use:
//
//   $userdata_config['loginrules'] = 1
//   $userdata_config['loginrule1'] = 'user~=/(.+)@(.+)/ user={1}.{2}
//
// If you also needed to convert any 'admin@domain.com' type of logins
// into a different format, such into 'domain.com', you could use:
// 
//   $userdata_config['loginrules'] = 2
//   $userdata_config['loginrule1'] = 'user~=/^admin@(.+)/ user={1}'
//   $userdata_config['loginrule2'] = 'user~=/(.+)@(.+)/ user={1}.{2}
//
$userdata_config['loginrules'] = 0;
//$userdata_config['loginrule1'] = '';



?>
