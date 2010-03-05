<?php

require_once("../../../../../../wp-config.php");

$role = "subscriber"; // Only include people who currently write

  global $wpdb;  
  $writers = $wpdb->get_results(' 
    SELECT ID, display_name, user_login
    FROM '.$wpdb->users.' INNER JOIN '.$wpdb->usermeta.' 
    ON '.$wpdb->users.'.ID = '.$wpdb->usermeta.'.user_id 
    WHERE '.$wpdb->usermeta.'.meta_key = \''.$wpdb->prefix.'capabilities\' 
    AND '.$wpdb->usermeta.'.meta_value NOT LIKE \'%"'.$role.'"%\' 
  ');  

$writer = array(
        "id" => $input->id,
        "result" => $writers,
        "error" => null);

if(function_exists('json_encode')) {
  echo json_encode($writer);
} else {
  // PHP4 version
  require_once(ABSPATH."/wp-includes/js/tinymce/plugins/spellchecker/classes/utils/JSON.php");
  $json_obj = new Moxiecode_JSON();
  echo $json_obj->encode($writer);
}

?>
