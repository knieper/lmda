<?php
/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 */

/**
 * Class CRM_Mailing_MailStore_Maildir
 */
class CRM_Mailing_MailStore_Maildir extends CRM_Mailing_MailStore {

  /**
   * Connect to the supplied dir and make sure the two mail dirs exist.
   *
   * @param string $dir
   *   Dir to operate upon.
   *
   * @return \CRM_Mailing_MailStore_Maildir
   */
  public function __construct($dir) {
    $this->_dir = $dir;

    $this->_ignored = $this->maildir(implode(DIRECTORY_SEPARATOR, [
      'CiviMail.ignored',
      date('Y'),
      date('m'),
      date('d'),
    ]));
    $this->_processed = $this->maildir(implode(DIRECTORY_SEPARATOR, [
      'CiviMail.processed',
      date('Y'),
      date('m'),
      date('d'),
    ]));
  }

  /**
   * Return the next X messages from the mail store.
   * FIXME: in CiviCRM 2.2 this always returns all the emails
   *
   * @param int $count
   *   Number of messages to fetch FIXME: ignored in CiviCRM 2.2 (assumed to be 0, i.e., fetch all).
   *
   * @return array
   *   array of ezcMail objects
   */
  public function fetchNext($count = 0) {
    $mails = [];
    $parser = new ezcMailParser();
    // set property text attachment as file CRM-5408
    $parser->options->parseTextAttachmentsAsFiles = TRUE;

    foreach ([
      'cur',
      'new',
    ] as $subdir) {
      $dir = $this->_dir . DIRECTORY_SEPARATOR . $subdir;
      foreach (scandir($dir) as $file) {
        if ($file == '.' or $file == '..') {
          continue;
        }
        $path = $dir . DIRECTORY_SEPARATOR . $file;

        if ($this->_debug) {

          print "retrieving message $path\n";

        }

        $set = new ezcMailFileSet([$path]);
        $single = $parser->parseMail($set);
        $mails[$path] = $single[0];
      }
    }
    return $mails;
  }

  /**
   * Fetch the specified message to the local ignore folder.
   *
   * @param int $file
   *   File location of the message to fetch.
   *
   * @throws Exception
   */
  public function markIgnored($file) {
    if ($this->_debug) {
      print "moving $file to ignored folder\n";
    }
    $target = $this->_ignored . DIRECTORY_SEPARATOR . basename($file);
    if (!rename($file, $target)) {
      throw new Exception("Could not rename $file to $target");
    }
  }

  /**
   * Fetch the specified message to the local processed folder.
   *
   * @param int $file
   *   File location of the message to fetch.
   *
   * @throws Exception
   */
  public function markProcessed($file) {
    if ($this->_debug) {
      print "moving $file to processed folder\n";
    }
    $target = $this->_processed . DIRECTORY_SEPARATOR . basename($file);
    if (!rename($file, $target)) {
      throw new Exception("Could not rename $file to $target");
    }
  }

}
