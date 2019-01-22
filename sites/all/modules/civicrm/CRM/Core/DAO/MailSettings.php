<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2019
 *
 * Generated from xml/schema/CRM/Core/MailSettings.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:419c207b65557941ee6f58e31d1bb6d8)
 */

/**
 * Database access object for the MailSettings entity.
 */
class CRM_Core_DAO_MailSettings extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_mail_settings';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  static $_log = FALSE;

  /**
   * primary key
   *
   * @var int unsigned
   */
  public $id;

  /**
   * Which Domain is this match entry for
   *
   * @var int unsigned
   */
  public $domain_id;

  /**
   * name of this group of settings
   *
   * @var string
   */
  public $name;

  /**
   * whether this is the default set of settings for this domain
   *
   * @var boolean
   */
  public $is_default;

  /**
   * email address domain (the part after @)
   *
   * @var string
   */
  public $domain;

  /**
   * optional local part (like civimail+ for addresses like civimail+s.1.2@example.com)
   *
   * @var string
   */
  public $localpart;

  /**
   * contents of the Return-Path header
   *
   * @var string
   */
  public $return_path;

  /**
   * name of the protocol to use for polling (like IMAP, POP3 or Maildir)
   *
   * @var string
   */
  public $protocol;

  /**
   * server to use when polling
   *
   * @var string
   */
  public $server;

  /**
   * port to use when polling
   *
   * @var int unsigned
   */
  public $port;

  /**
   * username to use when polling
   *
   * @var string
   */
  public $username;

  /**
   * password to use when polling
   *
   * @var string
   */
  public $password;

  /**
   * whether to use SSL or not
   *
   * @var boolean
   */
  public $is_ssl;

  /**
   * folder to poll from when using IMAP, path to poll from when using Maildir, etc.
   *
   * @var string
   */
  public $source;

  /**
   * Name of status to use when creating email to activity.
   *
   * @var string
   */
  public $activity_status;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_mail_settings';
    parent::__construct();
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static ::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'domain_id', 'civicrm_domain', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Mail Settings ID'),
          'description' => ts('primary key'),
          'required' => TRUE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'domain_id' => [
          'name' => 'domain_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Mail Settings Domain'),
          'description' => ts('Which Domain is this match entry for'),
          'required' => TRUE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
          'FKClassName' => 'CRM_Core_DAO_Domain',
          'pseudoconstant' => [
            'table' => 'civicrm_domain',
            'keyColumn' => 'id',
            'labelColumn' => 'name',
          ]
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Mail Settings Name'),
          'description' => ts('name of this group of settings'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'is_default' => [
          'name' => 'is_default',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Is Default Mail Settings?'),
          'description' => ts('whether this is the default set of settings for this domain'),
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'domain' => [
          'name' => 'domain',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('email Domain'),
          'description' => ts('email address domain (the part after @)'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'localpart' => [
          'name' => 'localpart',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('email Local Part'),
          'description' => ts('optional local part (like civimail+ for addresses like civimail+s.1.2@example.com)'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'return_path' => [
          'name' => 'return_path',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Return Path'),
          'description' => ts('contents of the Return-Path header'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'protocol' => [
          'name' => 'protocol',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Protocol'),
          'description' => ts('name of the protocol to use for polling (like IMAP, POP3 or Maildir)'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'optionGroupName' => 'mail_protocol',
            'optionEditPath' => 'civicrm/admin/options/mail_protocol',
          ]
        ],
        'server' => [
          'name' => 'server',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Mail Server'),
          'description' => ts('server to use when polling'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'port' => [
          'name' => 'port',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Mail Port'),
          'description' => ts('port to use when polling'),
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'username' => [
          'name' => 'username',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Mail Account Username'),
          'description' => ts('username to use when polling'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'password' => [
          'name' => 'password',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Mail Account Password'),
          'description' => ts('password to use when polling'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'is_ssl' => [
          'name' => 'is_ssl',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Mail Account Uses SSL'),
          'description' => ts('whether to use SSL or not'),
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'source' => [
          'name' => 'source',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Mail Folder'),
          'description' => ts('folder to poll from when using IMAP, path to poll from when using Maildir, etc.'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
        ],
        'activity_status' => [
          'name' => 'activity_status',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Activity Status'),
          'description' => ts('Name of status to use when creating email to activity.'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mail_settings',
          'entity' => 'MailSettings',
          'bao' => 'CRM_Core_BAO_MailSettings',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'optionGroupName' => 'activity_status',
            'keyColumn' => 'name',
            'optionEditPath' => 'civicrm/admin/options/activity_status',
          ]
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'mail_settings', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'mail_settings', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
