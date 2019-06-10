<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2019
 *
 * Generated from xml/schema/CRM/Contact/DashboardContact.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:f9be53bdf3d5151edf77e9b9f9004571)
 */

/**
 * Database access object for the DashboardContact entity.
 */
class CRM_Contact_DAO_DashboardContact extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_dashboard_contact';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = FALSE;

  /**
   * @var int unsigned
   */
  public $id;

  /**
   * Dashboard ID
   *
   * @var int unsigned
   */
  public $dashboard_id;

  /**
   * Contact ID
   *
   * @var int unsigned
   */
  public $contact_id;

  /**
   * column no for this widget
   *
   * @var boolean
   */
  public $column_no;

  /**
   * Is this widget active?
   *
   * @var boolean
   */
  public $is_active;

  /**
   * Ordering of the widgets.
   *
   * @var int
   */
  public $weight;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_dashboard_contact';
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
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'dashboard_id', 'civicrm_dashboard', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'contact_id', 'civicrm_contact', 'id');
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
          'title' => ts('Dashboard Contact ID'),
          'required' => TRUE,
          'where' => 'civicrm_dashboard_contact.id',
          'table_name' => 'civicrm_dashboard_contact',
          'entity' => 'DashboardContact',
          'bao' => 'CRM_Contact_BAO_DashboardContact',
          'localizable' => 0,
        ],
        'dashboard_id' => [
          'name' => 'dashboard_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Dashboard'),
          'description' => ts('Dashboard ID'),
          'required' => TRUE,
          'where' => 'civicrm_dashboard_contact.dashboard_id',
          'table_name' => 'civicrm_dashboard_contact',
          'entity' => 'DashboardContact',
          'bao' => 'CRM_Contact_BAO_DashboardContact',
          'localizable' => 0,
          'FKClassName' => 'CRM_Core_DAO_Dashboard',
        ],
        'contact_id' => [
          'name' => 'contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Dashboard Contact'),
          'description' => ts('Contact ID'),
          'required' => TRUE,
          'where' => 'civicrm_dashboard_contact.contact_id',
          'table_name' => 'civicrm_dashboard_contact',
          'entity' => 'DashboardContact',
          'bao' => 'CRM_Contact_BAO_DashboardContact',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
        ],
        'column_no' => [
          'name' => 'column_no',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Column No'),
          'description' => ts('column no for this widget'),
          'where' => 'civicrm_dashboard_contact.column_no',
          'default' => '0',
          'table_name' => 'civicrm_dashboard_contact',
          'entity' => 'DashboardContact',
          'bao' => 'CRM_Contact_BAO_DashboardContact',
          'localizable' => 0,
        ],
        'is_active' => [
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Dashlet is Active?'),
          'description' => ts('Is this widget active?'),
          'where' => 'civicrm_dashboard_contact.is_active',
          'default' => '0',
          'table_name' => 'civicrm_dashboard_contact',
          'entity' => 'DashboardContact',
          'bao' => 'CRM_Contact_BAO_DashboardContact',
          'localizable' => 0,
        ],
        'weight' => [
          'name' => 'weight',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Order'),
          'description' => ts('Ordering of the widgets.'),
          'where' => 'civicrm_dashboard_contact.weight',
          'default' => '0',
          'table_name' => 'civicrm_dashboard_contact',
          'entity' => 'DashboardContact',
          'bao' => 'CRM_Contact_BAO_DashboardContact',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'dashboard_contact', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'dashboard_contact', $prefix, []);
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
    $indices = [
      'index_dashboard_id_contact_id' => [
        'name' => 'index_dashboard_id_contact_id',
        'field' => [
          0 => 'dashboard_id',
          1 => 'contact_id',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_dashboard_contact::1::dashboard_id::contact_id',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
