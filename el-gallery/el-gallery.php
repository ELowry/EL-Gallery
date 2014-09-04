<?php
/*
Plugin Name: EL-Gallery
Plugin URI: http://wordpress.org/plugins/el-gallery/
Description: An extremely simplistic gallery replacement plugin.
Version: 1.2.4
Author: Eric Lowry
Author URI: http://ericlowry.fr/
License: GPL2
*/

// We initiate the translation
add_action('init', 'el_gallery_translation_init');
// We initiate jquery
wp_enqueue_script( 'jquery' );
// We initiate the css styling
wp_enqueue_style( 'el-gallery_style', plugins_url('/css/el-gallery.css', __FILE__ ) );
// We call upon Font Awsome
wp_enqueue_style( 'el-gallery_font_awsome', plugins_url('/css/font-awesome.min.css', __FILE__ ) );

// We call the translation
function el_gallery_translation_init() {
	load_plugin_textdomain('el-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// We call the admin page in the admin pannel
if (is_admin()){
	include('el-gallery-admin.php');
}



// We get the gallery's attributes and modify them according to options
function prepare_el_gallery_shortcode($atts){
	for($i = 0; $i < 3; $i++) { 
		extract(shortcode_atts(array(
			'orderby' => 'menu_order ASC, ID ASC',
			'include' => '',
			'id' => $post->ID,
			'itemtag' => 'dl',
			'icontag' => 'dt',
			'captiontag' => 'dd',
			'columns' => 3,
			'size' => 'full',
			'link' => 'file'
		), $atts));

		if ( wpmd_is_phone() && get_option('el_gallery_mobile_detect') ) {
			$size = 'medium';
		}
		$size_full = $size;

		if ($i == 1) {
			$size = 'thumbnail';
			$size_thumb = $size;
		}

		$args = array(
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'post_mime_type' => 'image',
			'orderby' => $orderby
		);

		if ( !empty($include) ) {
			$args['include'] = $include;
		} else {
			$args['post_parent'] = $id;
			$args['numberposts'] = -1;
		}

		if ($i == 0) {
			$images_full = get_posts($args);
		} else {
			$images_thumb = get_posts($args);
			return array($images_full, $size_full, $images_thumb, $size_thumb);
		}
	};

};

// We replace the gallery shortcode
remove_shortcode('gallery');
add_shortcode('gallery', 'el_gallery');


// We write our own shortcode
function el_gallery($atts) {

	global $post;

	if ( ! empty( $atts['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $atts['orderby'] ) )
			$atts['orderby'] = 'post__in';
		$atts['include'] = $atts['ids'];
	}

	// We set up the gallery
	$prepared = prepare_el_gallery_shortcode($atts);
	$images = $prepared[0];
	$size = $prepared[1];

	$duration = get_option('el_gallery_time') * 1000;
	$switch_width = get_option('el_gallery_width');
	$max_height = get_option('el_gallery_height');
	$nav = get_option('el_gallery_nav');
	$nav_color = get_option('el_gallery_nav_color');
	$nav_light = get_option('el_gallery_nav_light');
	$centered = get_option('el_gallery_center');
	$loading_icon = get_option('el_gallery_icon');
	wp_enqueue_script( 'el-gallery', plugins_url('/js/el-gallery.js', __FILE__ ) );
	wp_localize_script( 'el-gallery', 'el_gallery_parameters',array(
		'duration' => $duration,
		'switch_width' => $switch_width,
		'max_height' => $max_height,
		'nav' => $nav,
		'nav_color' => $nav_color,
		'nav_light' => $nav_light,
		'centered' => $centered
		));

	$print_gallery .= '<!-- EL-Gallery Plugin -->'."\r\n";
	$print_gallery .= '<figure class="el_gallery">';

	$print_gallery .= '<noscript><h5>'.__('To fully enjoy this website, it is necesairy to have activatedJavaScript. Here are <a href="http://www.enable-javascript.com/" target="_blank"> instructions on how to activate JavaScript for your browser</a>.','el-gallery').'</h5></noscript>';

	$print_gallery .= '<div class="el_gallery-slideshow_wrapper">';

	if($loading_icon == ""){
		$loading_icon == "cog";
	}
	$print_gallery .= '<div class="el_nav"><a href="#" class="el_nav-left"><span><i class="fa fa-caret-left"><div>&lt;</div></i></span></a><div class="el_loading"><i class="fa fa-'.$loading_icon.' fa-spin"><div>'.__('Loading...','el-gallery').'</div></i></div><a href="#" class="el_pause"><i class="fa fa-pause fa-fw"><div>'.__('Pause','el-gallery').'</div></i></a><a href="#" class="el_nav-right"><span><i class="fa fa-caret-right"><div>&gt;</div></i></span></a></div>';

	foreach ( $images as $image ) {
		$caption = $image->post_excerpt;

		$description = $image->post_content;
		if($description == '') $description = $image->post_title;

		$image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);

		$url_info = wp_get_attachment_image_src($image->ID, $size);

		// We render the gallery
		if (get_option('el_gallery_links') == true) {
			$print_gallery .= '<a href="'.$url_info['0'].'">';
		}
		$print_gallery .= wp_get_attachment_image($image->ID, $size, false, array('itemprop'=>'image') );
		$print_gallery .= '</a>';
	}
	$print_gallery .= '</div>';



	// We set up the thumbnails
	$image = $prepared[2];
	$size = $prepared[3];

	if ( wpmd_is_notphone() && sizeof($images) < 8 && $centered == true ) {
		$thumbs_padding = (100 - sizeof($images) * 12.5) / 2;
	} elseif ( sizeof($images) < 5 && $centered == true ) {
		$thumbs_padding = (100 - sizeof($images) * 20) / 2;
	} else {
		$thumbs_padding = 0;
	}

	if (wpmd_is_notphone()) {
		$thumbs_size = "10%";
	} else {
		$thumbs_size = "16%";
	}

	$print_gallery .= '<figcaption class="el_gallery-thumbnails_wrapper" style="padding-left:'.$thumbs_padding.'%;">';
	foreach ( $images as $image ) {
		$caption = $image->post_excerpt;

		$description = $image->post_content;
		if($description == '') $description = $image->post_title;

		$image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);

		// We render the thumbnails
		$print_gallery .= wp_get_attachment_image($image->ID, $size);
	}
	$print_gallery .= '</figcaption>';

	$print_gallery .= '</figure>'."\r\n";
	return $print_gallery;
}

?>