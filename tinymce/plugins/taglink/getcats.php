<?php

require_once("../../../../../../wp-config.php");

global $wpdb;

//$cat = get_categories();
$regexp = "'[0-9a-zA-Z]'";
if($_GET['col'] == 1) {
  $regexp = "'^[0-9a-lA-L]'";
}
if($_GET['col'] == 2) {
  $regexp = "'^[m-zM-Z]'";
}

$query = "SELECT $wpdb->terms.term_ID AS `cat_ID`,
  $wpdb->terms.name AS `cat_name`,
  $wpdb->term_taxonomy.description AS `category_description`
  FROM $wpdb->terms, $wpdb->term_taxonomy
  WHERE $wpdb->terms.term_ID = $wpdb->term_taxonomy.term_id
  AND $wpdb->term_taxonomy.taxonomy = 'category'
  AND $wpdb->terms.name REGEXP $regexp
  GROUP BY $wpdb->terms.term_id ORDER BY name ASC";
$cat = $wpdb->get_results($query);

$cats = array(
        "id" => $input->id,
        "result" => $cat,
        "error" => null);

if(function_exists('json_encode')) {
  echo json_encode($cats);
} else {
  // PHP4 version
  require_once(ABSPATH."/wp-includes/js/tinymce/plugins/spellchecker/classes/utils/JSON.php");
  $json_obj = new Moxiecode_JSON();
  echo $json_obj->encode($cats);
}

?>
