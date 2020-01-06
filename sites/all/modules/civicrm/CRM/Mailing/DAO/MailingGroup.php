<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from xml/schema/CRM/Mailing/MailingGroup.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:c4fc1c2b7ccba1f63edbf40993dae6ea)
 */

/**
 * Database access object for the MailingGroup entity.
 */
class CRM_Mailing_DAO_MailingGroup extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_mailing_group';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = FALSE;

  /**
   * @var int
   */
  public $id;

  /**
   * The ID of a previous mailing to include/exclude recipients.
   *
   * @var int
   */
  public $mailing_id;

  /**
   * Are the members of the group included or excluded?.
   *
   * @var string
   */
  public $group_type;

  /**
   * Name of table where item being referenced is stored.
   *
   * @var string
   */
  public $entity_table;

  /**
   * Foreign key to the referenced item.
   *
   * @var int
   */
  public $entity_id;

  /**
   * The filtering search. custom search id or -1 for civicrm api search
   *
   * @var int
   */
  public $search_id;

  /**
   * The arguments to be sent to the search function
   *
   * @var text
   */
  public $search_args;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_mailing_group';
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
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'mailing_id', 'civicrm_mailing', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Dynamic(self::getTableName(), 'entity_id', NULL, 'id', 'entity_table');
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
          'title' => ts('Mailing Group ID'),
          'required' => TRUE,
          'where' => 'civicrm_mailing_group.id',
          'table_name' => 'civicrm_mailing_group',
          'entity' => 'MailingGroup',
          'bao' => 'CRM_Mailing_DAO_MailingGroup',
          'localizable' => 0,
        ],
        'mailing_id' => [
          'name' => 'mailing_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Mailing'),
          'description' => ts('The ID of a previous mailing to include/exclude recipients.'),
          'required' => TRUE,
          'where' => 'civicrm_mailing_group.mailing_id',
          'table_name' => 'civicrm_mailing_group',
          'entity' => 'MailingGroup',
          'bao' => 'CRM_Mailing_DAO_MailingGroup',
          'localizable' => 0,
          'FKClassName' => 'CRM_Mailing_DAO_Mailing',
        ],
        'group_type' => [
          'name' => 'group_type',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Mailing Group Type'),
          'description' => ts('Are the members of the group included or excluded?.'),
          'maxlength' => 8,
          'size' => CRM_Utils_Type::EIGHT,
          'where' => 'civicrm_mailing_group.group_type',
          'table_name' => 'civicrm_mailing_group',
          'entity' => 'MailingGroup',
          'bao' => 'CRM_Mailing_DAO_MailingGroup',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Core_SelectValues::getMailingGroupTypes',
          ],
        ],
        'entity_table' => [
          'name' => 'entity_table',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Mailing Group Entity Table'),
          'description' => ts('Name of table where item being referenced is stored.'),
          'required' => TRUE,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_mailing_group.entity_table',
          'table_name' => 'civicrm_mailing_group',
          'entity' => 'MailingGroup',
          'bao' => 'CRM_Mailing_DAO_MailingGroup',
          'localizable' => 0,
          'pseudoconstant' => [
            'callback' => 'CRM_Mailing_BAO_Mailing::mailingGroupEntityTables',
          ],
        ],
        'entity_id' => [
          'name' => 'entity_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Mailing Group Entity'),
          'description' => ts('Foreign key to the referenced item.'),
          'required' => TRUE,
          'where' => 'civicrm_mailing_group.entity_id',
          'table_name' => 'civicrm_mailing_group',
          'entity' => 'MailingGroup',
          'bao' => 'CRM_Mailing_DAO_MailingGroup',
          'localizable' => 0,
        ],
        'search_id' => [
          'name' => 'search_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Mailing Group Search'),
          'description' => ts('The filtering search. custom search id or -1 for civicrm api search'),
          'where' => 'civicrm_mailing_group.search_id',
          'table_name' => 'civicrm_mailing_group',
          'entity' => 'MailingGroup',
          'bao' => 'CRM_Mailing_DAO_MailingGroup',
          'localizable' => 0,
        ],
        'search_args' => [
          'name' => 'search_args',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => ts('Mailing Group Search Arguments'),
          'description' => ts('The arguments to be sent to the search function'),
          'where' => 'civicrm_mailing_group.search_args',
          'table_name' => 'civicrm_mailing_group',
          'entity' => 'MailingGroup',
          'bao' => 'CRM_Mailing_DAO_MailingGroup',
          'localizable' => 0,
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'mailing_group', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'mailing_group', $prefix, []);
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
