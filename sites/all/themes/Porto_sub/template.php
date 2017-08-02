<?php
/*
 * Prefix your custom functions with porto_sub. For example:
 * porto_sub_form_alter(&$form, &$form_state, $form_id) { ... }
 */

//hide users who would like to not be shown to the public but should still be in our database and be able to login
function porto_sub_preprocess_user_profile(&$variables){
  //dpm($variables);

  //check for admin privilege of viewer
  $isAdmin = FALSE;
  if(user_has_role('3', $variables['user'])){
    $isAdmin = TRUE;
  }
  //dpm($isAdmin);

  //check if the viewer is the owner of the account
  $isViewer = FALSE;
  if($variables['user']->uid == $variables['elements']['#account']->uid){
    $isViewer = TRUE;
  }
  //dpm($isViewer);

  //check if user is an admin or is the owner of the account
  if(($isAdmin !== TRUE) && ($isViewer !== TRUE)) {
      //don't redirect if on the edit page or if the field is empty
    if((arg(2) != 'edit') && (!empty($variables['field_hide_from_public_display']))){
      //redirect to home page
        drupal_goto('/');
    }
  }
  //hide field from all public views
  unset($variables['field_hide_from_public_display']);
  unset($variables['elements']['field_hide_from_public_display']);
  unset($variables['user_profile']['field_hide_from_public_display']);
}
