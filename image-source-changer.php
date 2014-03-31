<?php
/**
 * @package Image Source Changer
 * @version 1.0
 */
/*
Plugin Name: Image Source Changer
Plugin URI: http://themightymo.com
Description: This is a plugin that will update the source of the image. 
Author: Sherwin Calims
Version: 1.0
Author URI: http://sherkspear.com
*/


/**Plugin Menu Configuration**/

// create custom plugin settings menu
add_action('admin_menu', 'isc_create_menu');

function isc_create_menu() {

	//create new top-level menu
	add_menu_page('Image Source Changer Settings', 'ISC Settings', 'administrator', __FILE__, 'isc_settings_page',plugins_url('/images/icon.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	//register our settings
	register_setting( 'image-source-changer-group', 'key_search' );
	register_setting( 'image-source-changer-group', 'remote_url' );
}

function isc_settings_page() {
?>
<div class="wrap">
<h2>Image Source Changer</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'image-source-changer-group' ); ?>
    <?php do_settings_sections( 'image-source-changer-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Key Search ex. uploads</th>
        <td><input type="text" size="50" name="key_search" value="<?php echo get_option('key_search'); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Remote Url ex. http://livesite.com/wp-content/</th>
        <td><input type="text" size="50" name="remote_url" value="<?php echo get_option('remote_url'); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } 
/**Plugin Menu Configuration**/

$script_params = array(
    'keySearch' => get_option('key_search'),
    'remoteUrl' => get_option('remote_url'),
);
wp_register_script( 'isc-handle', dirname(__FILE__).'/myscript.js' );
wp_localize_script( 'isc-handle', 'scriptParams', $script_params );
wp_enqueue_script( 'isc-handle' );

function add_this_script_footer(){ ?>
  <script type="text/javascript">
    jQuery(document).ready(function () {
        var key_search=scriptParams.keySearch; 
		var remote_url=scriptParams.remoteUrl;
		jQuery('img').each(function () {
			   var curSrc = jQuery(this).attr('src');
			   var indexFound=curSrc.indexOf(key_search);
			   if(-1<indexFound){
				  jQuery(this).attr("src",remote_url+curSrc.substr(indexFound));
			   }
		});
	});
  </script>	

<?php }

add_action('wp_footer', 'add_this_script_footer', 100); 
