<?php
/*
Plugin Name: EL-Gallery
Plugin URI: http://wordpress.org/plugins/el-gallery/
Description: An extremely simplistic gallery replacement plugin.
Version: 1.0
Author: Eric Lowry
Author URI: http://ericlowry.fr/
License: GPL2


TODO :
 -> Add an option to choose quality of link-images
*/

add_action('init', 'el_gallery_translation_init');
wp_enqueue_script( 'jquery' );
wp_enqueue_style( 'el-gallery_style', plugins_url('/css/el-gallery.css', __FILE__ ) );

// This initiates translation
function el_gallery_translation_init() {
	load_plugin_textdomain('el-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}


if (is_admin()){
	include('el-gallery-admin.php');
}



	function prepare_el_gallery_shortcode($type,$atts){
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

		if ($type == "thumbnails") {
			$size = 'thumbnail';
		}

		$args = array(
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'post_mime_type' => 'image',
			'orderby' => $orderby
		);

		if ( !empty($include) )
			$args['include'] = $include;
		else {
			$args['post_parent'] = $id;
			$args['numberposts'] = -1;
		}

		$images = get_posts($args);

		return array($images, $size);

	};

remove_shortcode('gallery');
add_shortcode('gallery', 'el_gallery');


function el_gallery($atts) {

	global $post;

	if ( ! empty( $atts['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $atts['orderby'] ) )
			$atts['orderby'] = 'post__in';
		$atts['include'] = $atts['ids'];
	}


	$prepared = prepare_el_gallery_shortcode('',$atts);
	$images = $prepared[0];
	$size = $prepared[1];

	$duration = get_option('el_gallery_time') * 1000;
	$switch_width = get_option('el_gallery_width');
	$centered = get_option('el_gallery_center');
	$max_height = get_option('el_gallery_height');
	wp_enqueue_script( 'el-gallery', plugins_url('/js/el-gallery.js', __FILE__ ) );
	wp_localize_script( 'el-gallery', 'el_gallery_parameters',array(
		'duration' => $duration,
		'switch_width' => $switch_width,
		'centered' => $centered,
		'max_height' => $max_height
		));

	$print_gallery .= '<!-- EL-Gallery Plugin -->'."\r\n";
	$print_gallery .= '<figure class="el_gallery">';

	$print_gallery .= '<noscript><h5>'.__('To fully enjoy this website, it is necesairy to have activatedJavaScript. Here are <a href="http://www.enable-javascript.com/" target="_blank"> instructions on how to activate JavaScript for your browser</a>.','el-gallery').'</h5></noscript>';

	$print_gallery .= '<div class="el_gallery-slideshow_wrapper">';
	foreach ( $images as $image ) {
		$caption = $image->post_excerpt;

		$description = $image->post_content;
		if($description == '') $description = $image->post_title;

		$image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);

		$url_info = wp_get_attachment_image_src($image->ID, $size);

		// render your gallery here
		if (get_option('el_gallery_links') == true) {
			$print_gallery .= '<a href="'.$url_info['0'].'">';
		}
		$print_gallery .= wp_get_attachment_image($image->ID, $size);
		$print_gallery .= '</a>';
	}
	$print_gallery .= '</div>';



	$prepared = prepare_el_gallery_shortcode("thumbnails",$atts);
	$image = $prepared[0];
	$size = $prepared[1];

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

		// render your gallery here
		$print_gallery .= wp_get_attachment_image($image->ID, $size);
	}
	$print_gallery .= '</figcaption>';

	$print_gallery .= '</figure>'."\r\n";
	return $print_gallery;
}


?>