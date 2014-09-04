/*
Plugin Name: EL-Gallery
Description: An extremely simplistic gallery replacement plugin.
Version: 1.2.4
Author: Eric Lowry
Author URI: http://ericlowry.fr/
License: GPL2
*/

( function($, data){

	var nav = data.nav,
		nav_color = data.nav_color;

	$('.el-admin_toggler').each(function(){
		var current = $(this);
		if ( nav == true || $('.el-admin_toggler_box',current).attr('checked') == 'checked') {
			$('.el-admin_toggle',current).css('display', 'block');
		}
		$('.el-admin_toggler_box',this).bind("click", function(event) {
			$('.el-admin_toggle',current).slideToggle();
		});
	});

})( jQuery, el_gallery_admin_parameters );