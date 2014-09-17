/*
Plugin Name: EL-Gallery
Description: An extremely simplistic gallery replacement plugin.
Version: 1.2.6a
Author: Eric Lowry
Author URI: http://ericlowry.fr/
License: GPL2
*/

( function($){

	$('.el-admin_toggler').each(function(){
		var current = $(this);
		$('.el-admin_toggler_box',this).bind("click", function(event) {
			$('.el-admin_toggle',current).slideToggle();
			$('i.el-admin_toggler_box',current).toggleClass('fa-caret-right').toggleClass('fa-caret-down');
		});
	});

})( jQuery );