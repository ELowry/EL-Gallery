<?php
/*
Plugin Name: EL-Gallery
Plugin URI: http://ericlowry.fr/
Description: An extremely simplistic gallery replacement plugin.
Version: 0.91
Author: EricL owry
Author URI: http://ericlowry.fr/
License: GPL2


TODO :
 -> Set up an options-menu in the control pannel
 -> Make multiple-line thumbnails centered
 -> Set up translations for no-javascript message
*/

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'el-gallery', plugins_url('/js/el-gallery.js', __FILE__ ) );
wp_enqueue_style( 'el-gallery', plugins_url('/css/el-gallery.css', __FILE__ ) );

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
	
	if ( wpmd_is_phone() ) {
		$size = 'medium';
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
	
	$print_gallery .= '<figure class="el_gallery">';
	
	$print_gallery .= '<noscript><p>Pour profiter pleinement de ce site, il faut activer JavaScript. Voici des <a href="http://www.enable-javascript.com/fr" target="_blank">instructions pour activer le JavaScript dans votre navigateur</a>.</p></noscript>';
	
	$print_gallery .= '<div class="el_gallery-slideshow_wrapper">';
	foreach ( $images as $image ) {
		$caption = $image->post_excerpt;

		$description = $image->post_content;
		if($description == '') $description = $image->post_title;

		$image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);
		
		$url_info = wp_get_attachment_image_src($image->ID, $size);

		// render your gallery here
		$print_gallery .= '<a href="'.$url_info['0'].'">';
		$print_gallery .= wp_get_attachment_image($image->ID, $size);
		$print_gallery .= '</a>';
	}
	$print_gallery .= '</div>';
	
	
	
	extract(shortcode_atts(array(
		'orderby' => 'menu_order ASC, ID ASC',
		'include' => '',
		'id' => $post->ID,
		'itemtag' => 'dl',
		'icontag' => 'dt',
		'captiontag' => 'dd',
		'columns' => 3,
		'size' => 'thumbnail',
		'link' => 'file'
	), $atts));

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
	
	if ( wpmd_is_notphone() && sizeof($images) < 8 ) {
		$thumbs_padding = (100 - sizeof($images) * 12.5) / 2;
	} elseif ( sizeof($images) < 4 ) {
		$thumbs_padding = (100 - sizeof($images) * 20) / 2;
	} else {
		$thumbs_padding = 0;
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
	
	$print_gallery .= '</figure>';
	return $print_gallery;
}

?>
