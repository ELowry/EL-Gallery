<?php
/*
Plugin: EL-Gallery
Description: An extremely simplistic gallery replacement plugin.
Version: 1.2.4
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
	$opt_height = 'el_gallery_height';
	$opt_nav = 'el_gallery_nav';
	$opt_nav_color = 'el_gallery_nav_color';
	$opt_nav_light = 'el_gallery_nav_light';
	$opt_center = 'el_gallery_center';
	$opt_links = 'el_gallery_links';
	$opt_mobile_detect = 'el_gallery_mobile_detect';
	$opt_icon = 'el_gallery_icon';
	$data_field_time = 'el_gallery_time';
	$data_field_width = 'el_gallery_width';
	$data_field_height = 'el_gallery_height';
	$data_field_nav = 'el_gallery_nav';
	$data_field_nav_color = 'el_gallery_nav_color';
	$data_field_nav_light = 'el_gallery_nav_light';
	$data_field_center = 'el_gallery_center';
	$data_field_links = 'el_gallery_links';
	$data_field_mobile_detect = 'el_gallery_mobile_detect';
	$data_field_icon = 'el_gallery_icon';

	// Read in existing option values from database
	$opt_val_time = get_option( $opt_time );
	$opt_val_width = get_option( $opt_width );
	$opt_val_height = get_option( $opt_height );
	$opt_val_nav = get_option( $opt_nav );
	$opt_val_nav_color = get_option( $opt_nav_color );
	$opt_val_nav_light = get_option( $opt_nav_light );
	$opt_val_center = get_option( $opt_center );
	$opt_val_links = get_option( $opt_links );
	$opt_val_mobile_detect = get_option( $opt_mobile_detect );
	$opt_val_icon = get_option( $opt_icon );


	// See if the user has posted us some information
	// If they did, this hidden field will be set to true
	if( isset($_POST[ $hidden_field ]) && $_POST[ $hidden_field ] == true ) {
		// Read their posted value
		$opt_val_time = $_POST[ $data_field_time ];
		$opt_val_width = $_POST[ $data_field_width ];
		$opt_val_height = $_POST[ $data_field_height ];
		$opt_val_nav = $_POST[ $data_field_nav ];
		$opt_val_nav_color = $_POST[ $data_field_nav_color ];
		$opt_val_nav_light = $_POST[ $data_field_nav_light ];
		$opt_val_center = $_POST[ $data_field_center ];
		$opt_val_links = $_POST[ $data_field_links ];
		$opt_val_mobile_detect = $_POST[ $data_field_mobile_detect ];
		$opt_val_icon = $_POST[ $data_field_icon ];

		// Save the posted value in the database
		update_option( $opt_time, $opt_val_time );
		update_option( $opt_width, $opt_val_width );
		update_option( $opt_height, $opt_val_height );
		update_option( $opt_nav, $opt_val_nav );
		update_option( $opt_nav_color, $opt_val_nav_color );
		update_option( $opt_nav_light, $opt_val_nav_light );
		update_option( $opt_center, $opt_val_center );
		update_option( $opt_links, $opt_val_links );
		update_option( $opt_mobile_detect, $opt_val_mobile_detect );
		update_option( $opt_icon, $opt_val_icon );

		// Put a settings updated message on the screen

	// Prepare default values upon activate
	register_activation_hook( __FILE__, 'el_gallery_initiate_options' );
	function el_gallery_initiate_options($opt_time,$opt_width,$opt_height,$opt_center,$opt_links,$opt_mobile_detect,$opt_icon){
		add_option($opt_time, '10');
		add_option($opt_width, '600');
		add_option($opt_height, '0.8');
		add_option($opt_nav, 'true');
		add_option($opt_nav_color, 'fff');
		add_option($opt_nav_light, 'false');
		add_option($opt_center, 'true');
		add_option($opt_links, 'true');
		add_option($opt_mobile_detect, 'false');
		add_option($opt_icon, 'cog');
	}

	// Remove options upon deactivate
	register_deactivation_hook( __FILE__, 'el_gallery_remove_options' );
	function el_gallery_remove_options($opt_time,$opt_width,$opt_height,$opt_center,$opt_links,$opt_mobile_detect,$opt_icon){
		remove_option($opt_time);
		remove_option($opt_width);
		remove_option($opt_height);
		remove_option($opt_nav);
		remove_option($opt_nav_color);
		remove_option($opt_nav_light);
		remove_option($opt_center);
		remove_option($opt_links);
		remove_option($opt_mobile_detect);
		remove_option($opt_icon);
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

	// We load the admin-specific javascript
	wp_enqueue_script( 'el-gallery_admin_script', plugins_url('/js/el-gallery_admin.js', __FILE__ ) );
	wp_localize_script( 'el-gallery_admin_script', 'el_gallery_admin_parameters',array(
		'nav' => $opt_val_nav,
		'nav_color' => $opt_val_nav_color
		));

	// Now display the settings editing screen

	echo '<div class="wrap">';

	// header

	echo "<h2>" . __( 'EL-Gallery Plugin Settings', 'el-gallery' ) . "</h2>";

	// settings form

	?>

<details>
	<p>EL-Gallery is an elegant ultra-lightweight javascript & css gallery replacement for WordPress.</p>
    <p>Feel free to rate/review, validate and/or ask questions on <a href="http://wordpress.org/plugins/el-gallery/" target="_blank">this plugin's webpage</a>.</p>
</details>

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
		<label><?php _e("Maximum Aspect Ratio: ", 'el-gallery' ); ?></label>
		<input type="input" name="<?php echo $data_field_height; ?>" value="<?php echo $opt_val_height; ?>" size="10">
		<span class="description"><?php _e( 'This option enables you to avoid tall images being too large (1:x aspect ratio). It can be overridden by using the "ratio" attribute in your gallery shortcode.', 'el-gallery' ); ?></span>
	</div>

	<hr />

	<div class="el-gallery_option el-admin_toggler">
		<input type="checkbox" class="el-admin_toggler_box" name="<?php echo $data_field_nav; ?>" value="true" <?php if($opt_val_nav == true){echo 'checked="checked"';}?>>
		<label><?php _e("Navigation Arrows: ", 'el-gallery' ); ?></label>
		<span class="description"><?php _e( 'Adds arrows on the right and left side of slides to navigate easily.', 'el-gallery' ); ?></span>

        <div class="el-admin_toggle">
            <div class="el-gallery_option">
                <label><?php _e("Background Color: ", 'el-gallery' ); ?></label>
                <input type="input" name="<?php echo $data_field_nav_color; ?>" value="<?php echo $opt_val_nav_color; ?>" size="7">
                <span class="description"><?php _e( "If your posts' backgrounds are not white, please input the <a href='http://www.colorpicker.com/' target='_blank'>hexadecimal code</a> of their background color.", 'el-gallery' ); ?></span>
            </div>
        
            <div class="el-gallery_option">
                <input type="checkbox" name="<?php echo $data_field_nav_light; ?>" value="true" <?php if($opt_val_nav_light == true){echo 'checked="checked"';}?>>
                <label><?php _e("White Arrows: ", 'el-gallery' ); ?></label>
                <span class="description"><?php _e( 'If the arrowsare too dark to be visible, activate this option to make them white.', 'el-gallery' ); ?></span>
            </div>
        </div>

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
		<label><?php _e("Clickable images: ", 'el-gallery' ); ?></label>
		<span class="description"><?php _e( 'Choose a loading icon : ', 'el-gallery' ); ?>
			<i class="fa fa-cog fa-spin"></i> <input type="radio" name="<?php echo $data_field_icon; ?>" value="cog" <?php if($opt_val_icon == "cog"){echo 'checked="checked"';}?>>
			<i class="fa fa-spinner fa-spin"></i> <input type="radio" name="<?php echo $data_field_icon; ?>" value="spinner" <?php if($opt_val_icon == "spinner"){echo 'checked="checked"';}?>>
			<i class="fa fa-refresh fa-spin"></i> <input type="radio" name="<?php echo $data_field_icon; ?>" value="refresh" <?php if($opt_val_icon == "refresh"){echo 'checked="checked"';}?>>
		</span>
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