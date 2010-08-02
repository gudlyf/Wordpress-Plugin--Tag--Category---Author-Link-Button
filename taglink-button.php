<?php
/*
Plugin Name: Tag, Category & Author Link Button
Plugin URI: http://cliqueclack.com/code/2010/03/05/wordpress-plugin-tag-category-and-author-link-button/
Description: New TinyMCE button to create tag, category or author link to selected text.
Author: Keith McDuffee
Version: 1.0
Author URI: http://cliqueclack.com
*/

function taglink_addbuttons() {
	// Don't bother doing this stuff if the current user lacks permissions
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;
 
	// Add only in Rich Editor mode
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "add_taglink_tinymce_plugin");
		add_filter('mce_buttons', 'register_taglink_button');
	}
}
 
function register_taglink_button($buttons) {
	array_push($buttons, "separator", "taglink");
	return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_taglink_tinymce_plugin($plugin_array) {
	$plugin_array['taglink'] = plugins_url('tag-category-author-link-button/tinymce/plugins/taglink/editor_plugin.js');
	return $plugin_array;
}

function taglink_footer_js() {
	global $wp_rewrite;

	$home_link = untrailingslashit( get_bloginfo('home') );
	$taglink = $wp_rewrite->get_tag_permastruct();
	$authorlink = $wp_rewrite->get_author_permastruct();
?>
<script type="text/javascript">
/* <![CDATA[ */
var taglink = '<?php echo $home_link . $taglink; ?>';
var authorlink = '<?php echo $home_link . $authorlink; ?>';

function getTagLink(tag) {
  tag = tag+'/';
  return taglink.replace(/%tag%/g,tag);
}

function getAuthorLink(author) {
  author = author+'/';
  return authorlink.replace(/%author%/g,author);
}

function getCatLink(catid) {
  var cat=new Array();
<?php
  $category_ids = get_all_category_ids();
  foreach($category_ids as $cat_id) :
?>
  cat[<?php echo $cat_id; ?>] = "<?php echo get_category_link($cat_id); ?>";
<?php
  endforeach;
?>
  return cat[catid];
}
/* ]]> */
</script>
<?php
}
			
add_action( 'init', 'taglink_addbuttons' );
add_action('admin_footer-post-new.php', 'taglink_footer_js');
add_action('admin_footer-post.php', 'taglink_footer_js');

?>
