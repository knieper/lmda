<?php
/**
 * Test Generated example of using message_template create API
 * *
 */
function message_template_create_example(){
$params = array(
  'msg_title' => 'msg_title_131',
  'msg_subject' => 'msg_subject_131',
  'msg_text' => 'msg_text_131',
  'msg_html' => 'msg_html_131',
  'workflow_id' => 131,
  'is_default' => '1',
  'is_reserved' => 1,
);

try{
  $result = civicrm_api3('message_template', 'create', $params);
}
catch (CiviCRM_API3_Exception $e) {
  // handle error here
  $errorMessage = $e->getMessage();
  $errorCode = $e->getErrorCode();
  $errorData = $e->getExtraParams();
  return array('error' => $errorMessage, 'error_code' => $errorCode, 'error_data' => $errorData);
}

return $result;
}

/**
 * Function returns array of result expected from previous function
 */
function message_template_create_expectedresult(){

  $expectedResult = array(
  'is_error' => 0,
  'version' => 3,
  'count' => 1,
  'id' => 2,
  'values' => array(
      '2' => array(
          'id' => '2',
          'msg_title' => 'msg_title_131',
          'msg_subject' => 'msg_subject_131',
          'msg_text' => 'msg_text_131',
          'msg_html' => 'msg_html_131',
          'is_active' => '1',
          'workflow_id' => '131',
          'is_default' => '1',
          'is_reserved' => '1',
          'pdf_format_id' => '',
        ),
    ),
);

  return $expectedResult;
}


/*
* This example has been generated from the API test suite. The test that created it is called
*
* testCreate and can be found in
* https://github.com/civicrm/civicrm-core/blob/master/tests/phpunit/api/v3/MessageTemplateTest.php
*
* You can see the outcome of the API tests at
* https://test.civicrm.org/job/CiviCRM-master-git/
*
* To Learn about the API read
* http://wiki.civicrm.org/confluence/display/CRMDOC/Using+the+API
*
* Browse the api on your own site with the api explorer
* http://MYSITE.ORG/path/to/civicrm/api/explorer
*
* Read more about testing here
* http://wiki.civicrm.org/confluence/display/CRM/Testing
*
* API Standards documentation:
* http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
*/
