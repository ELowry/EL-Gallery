<?php
/*
Plugin: EL-Gallery
Description: An extremely simplistic gallery replacement plugin.
Version: 0.94
Author: Eric Lowry
Author URI: http://ericlowry.fr/
License: GPL2
*/


// add_action creates the submenu
add_action('admin_init', 'el_gallery_admin_init');
add_action('admin_init', 'el_gallery_admin_translation_init');
add_action('admin_menu', 'el_gallery_admin_menu');

// Register the stylesheet
function el_gallery_admin_init() {
	wp_register_style( 'el-gallery_admin_style', plugins_url('/css/el-gallery_admin.css', __FILE__ ) );
}

// This initiates translation
function el_gallery_admin_translation_init() {
	load_plugin_textdomain('el-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// add_appearance_menu configures the submenu
function el_gallery_admin_menu(){
	$parent_slug = 'themes.php';
	$page_title = 'EL-Gallery Plugin Options';
	$menu_title = 'EL-Gallery';
	$capability = 'manage_options';
	$menu_slug = 'el-gallery';
	$function = 'el_gallery_settings_page';
	$icon_url = '';
	$position = 10;
	$el_gallery_page = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);

	add_action( 'admin_print_styles-' . $el_gallery_page, 'el_gallery_admin_styles' );
}

function el_gallery_admin_styles() {
	wp_enqueue_style( 'el-gallery_admin_style' );
}



// ADMIN MENU


// el_gallery_settings_page() displays the settings page content
function el_gallery_settings_page() {

	// Must check that the user has the required capability
	if (!current_user_can('manage_options'))
	{
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'el-gallery' ) );
	}

	// Variables for the field and option names
	$hidden_field = 'el_gallery_submit_hidden';
	$opt_time = 'el_gallery_time';
	$opt_width = 'el_gallery_width';
	$opt_center = 'el_gallery_center';
	$opt_links = 'el_gallery_links';
	$opt_mobile_detect = 'el_gallery_mobile_detect';
	$data_field_time = 'el_gallery_time';
	$data_field_width = 'el_gallery_width';
	$data_field_center = 'el_gallery_center';
	$data_field_links = 'el_gallery_links';
	$data_field_mobile_detect = 'el_gallery_mobile_detect';

	// Read in existing option values from database
	$opt_val_time = get_option( $opt_time );
	$opt_val_width = get_option( $opt_width );
	$opt_val_center = get_option( $opt_center );
	$opt_val_links = get_option( $opt_links );
	$opt_val_mobile_detect = get_option( $opt_mobile_detect );


	// See if the user has posted us some information
	// If they did, this hidden field will be set to true
	if( isset($_POST[ $hidden_field ]) && $_POST[ $hidden_field ] == true ) {
		// Read their posted value
		$opt_val_time = $_POST[ $data_field_time ];
		$opt_val_width = $_POST[ $data_field_width ];
		$opt_val_center = $_POST[ $data_field_center ];
		$opt_val_links = $_POST[ $data_field_links ];
		$opt_val_mobile_detect = $_POST[ $data_field_mobile_detect ];

		// Save the posted value in the database
		update_option( $opt_time, $opt_val_time );
		update_option( $opt_width, $opt_val_width );
		update_option( $opt_center, $opt_val_center );
		update_option( $opt_links, $opt_val_links );
		update_option( $opt_mobile_detect, $opt_val_mobile_detect );

		// Put a settings updated message on the screen

	// Prepare default values upon activate
	register_activation_hook( __FILE__, 'el_gallery_initiate_options' );
	function el_gallery_initiate_options($opt_time,$opt_width,$opt_center,$opt_links,$opt_mobile_detect){
		add_option($opt_time, '10');
		add_option($opt_width, '600');
		add_option($opt_center, 'true');
		add_option($opt_links, 'true');
		add_option($opt_mobile_detect, 'false');
	}

	// Remove options upon deactivate
	register_deactivation_hook( __FILE__, 'el_gallery_remove_options' );
	function el_gallery_remove_options($opt_time,$opt_width,$opt_center,$opt_links,$opt_mobile_detect){
		remove_option($opt_time);
		remove_option($opt_width);
		remove_option($opt_center);
		remove_option($opt_links);
		remove_option($opt_mobile_detect);
	}

	// Error Correction
	if (!is_numeric($opt_val_time)) {
		$opt_val_time = 10;
	}
	if ($opt_val_time < 2) {
		$opt_val_time = 2;
	}

?>
<div class="updated">
	<p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p>
</div>
<?php

	}

	// Now display the settings editing screen

	echo '<div class="wrap">';

	// header

	echo "<h2>" . __( 'EL-Gallery Plugin Settings', 'el-gallery' ) . "</h2>";

	// settings form

	?>

<form name="el-gallery_form" method="post" action="">
	<input type="hidden" name="<?php echo $hidden_field; ?>" value="true">

	<hr />

	<div class="el-gallery_option">
		<label><?php _e("Slide Duration: ", 'el-gallery' ); ?></label>
		<input type="input" name="<?php echo $data_field_time; ?>" value="<?php echo $opt_val_time; ?>" size="10">
		<span class="description"><?php _e( 'This is the duration of the slides in seconds. (Minimum: 2)', 'el-gallery' ); ?></span>
	</div>

	<hr />

	<div class="el-gallery_option">
		<label><?php _e("Transition Width: ", 'el-gallery' ); ?></label>
		<input type="input" name="<?php echo $data_field_width; ?>" value="<?php echo $opt_val_width; ?>" size="10">
		<span class="description"><?php _e( "When the window's width is inferior to this number, the thumbnails will go from 8 per line to 5 per line. (to disable: 0)", 'el-gallery' ); ?></span>
	</div>

	<hr />

	<div class="el-gallery_option">
		<input type="checkbox" name="<?php echo $data_field_center; ?>" value="true" <?php if($opt_val_center == true){echo 'checked="checked"';}?>>
		<label><?php _e("Centered Thumbnails: ", 'el-gallery' ); ?></label>
		<span class="description"><?php _e( 'This will center thumbnails. If deactivated, they will align to the left.', 'el-gallery' ); ?></span>
	</div>

	<hr />

	<div class="el-gallery_option">
		<input type="checkbox" name="<?php echo $data_field_links; ?>" value="true" <?php if($opt_val_links == true){echo 'checked="checked"';}?>>
		<label><?php _e("Clickable images: ", 'el-gallery' ); ?></label>
		<span class="description"><?php _e( 'By activating this, clicking on images in your gallery will open them in a separate tab. If you are using a lightbox plugin (like <a href="http://wordpress.org/plugins/simple-lightbox/" target="_blank">Simple Lightbox</a>), this might be necessairy for it to function.', 'el-gallery' ); ?></span>
	</div>

	<hr />

	<div class="el-gallery_option">
		<input type="checkbox" name="<?php echo $data_field_mobile_detect; ?>" value="true" <?php if($opt_val_mobile_detect == true){echo 'checked="checked"';}?>>
		<label><?php _e("Mobile Detect: ", 'el-gallery' ); ?></label>
		<span class="description"><?php _e( 'Activate this option if you have the <a href="http://wordpress.org/plugins/wp-mobile-detect/" target="_blank">WP Mobile Detect</a> plugin activated and want images to be loaded in "medium" resolution on smartphones.' ); ?></span>
	</div>

	<hr />

	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
	</p>

</form>
</div>

<?php

}
?>