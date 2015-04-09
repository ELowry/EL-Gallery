/*
Plugin Name: EL-Gallery
Description: An extremely simplistic gallery replacement plugin.
Version: 1.4.1
Author: Eric Lowry
Author URI: http://ericlowry.fr/
License: GPL2
*/

( function($){

	$(document).ready(function() {

		$('.el-admin_toggler').each(function(){
			var current = $(this);
			$('.el-admin_toggler_box',this).bind("click", function(event) {
				$('.el-admin_toggle',current).slideToggle();
				$('i.el-admin_toggler_box',current).toggleClass('el-icons-caret-right').toggleClass('el-icons-caret-down');
			});
		});

		$('#wpbody-content .wrap h2:first-child').html(function () {
			return $(this).text().replace('EL', '<i class="el-icons el-icons-el-gallery el-icons-logo" class="el-icons-logo"><div>EL</div></i>'); 
		});

	});

})( jQuery );