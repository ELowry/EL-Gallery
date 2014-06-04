/*
Plugin Name: EL-Gallery
Plugin URI: http://ericlowry.fr/
Description: An extremely simplistic gallery replacement plugin.
Version: 0.91
Author: Eric Lowry
Author URI: http://ericlowry.fr/
License: GPL2
*/

jQuery(function($){
	
	function start_slideshow() {
	
		// This function is the slideshow loop itsself
		function startloop(cntmax,cnt,type) {
			// We display the first/selected image correctly
			if ( type == false ) {
				$('.el_gallery-thumbnails_wrapper img').removeClass('current');
				$('.el_gallery-thumbnails_wrapper img:eq('+cnt+')').addClass('current');
				$('.el_gallery-slideshow_wrapper img').css('z-index','5');
				$('.el_gallery-slideshow_wrapper img:eq('+cnt+')').css('z-index','6');
				$('.el_gallery-slideshow_wrapper img').fadeOut('slow');
				$('.el_gallery-slideshow_wrapper img:eq('+cnt+')').fadeIn('slow');
			}
			if ( type != "first" ) {
				var gallery_height = $('.el_gallery-slideshow_wrapper img:eq('+cnt+')').height();
				$('.el_gallery-slideshow_wrapper').css('height', gallery_height);
				// We set up the interval
				clearInterval(loop_interval);
				loop_interval = setInterval(execute_loop,10000); // Change number to make the slides last more/less
			} else {
				// We wait for the first image to be loaded
				var image = new Image();
				image.onload = function () {
					setTimeout(function(){
						$('.el_gallery-slideshow_wrapper').css('background-image','none');
						startloop(cntmax,cnt,false);
					}, 1000); // the image has loaded, we display it
				}
				image.onerror = function () {
				   console.error("Cannot load image");
				}
				image.src = $('.el_gallery-thumbnails_wrapper img:eq(0)').attr('src');
			}
			function execute_loop() {
				cnt == cntmax ? cnt=0:cnt++
				$('.el_gallery-thumbnails_wrapper img').removeClass('current');
				$('.el_gallery-thumbnails_wrapper img:eq('+cnt+')').addClass('current');
				$('.el_gallery-slideshow_wrapper img').css('z-index','5');
				$('.el_gallery-slideshow_wrapper img:eq('+cnt+')').css('z-index','6');
				$('.el_gallery-slideshow_wrapper img').fadeOut('slow');
				$('.el_gallery-slideshow_wrapper img:eq('+cnt+')').fadeIn('slow');
				var gallery_height = $('.el_gallery-slideshow_wrapper img:eq('+cnt+')').height();
				$('.el_gallery-slideshow_wrapper').animate({'height': gallery_height}, 500);
			}
		}
		
		// We give the thumbnails a numbered id
		$(".el_gallery-thumbnails_wrapper img").each(function(index){
			var thumb_id = 'el_gallery-thumbnails-' + index;
			$(this).attr('id', thumb_id);
		});
		
		var cntmax = $('.el_gallery-slideshow_wrapper img').length - 1, // We check out how many images there are
			cnt = 0, // We start with the first image
			loop_interval = null;
		startloop(cntmax,cnt,"first"); // We load the slideshow loop for the first time
		
		// We setup the "skip-to" functions on thumbnails
		$(".el_gallery-thumbnails_wrapper img").bind("click", function(event) {
			cntmax = $('.el_gallery-slideshow_wrapper img').length - 1;
			var id = $(this).attr('id');
			cnt = id.substr(id.length - 1);
			if ( $('.el_gallery-slideshow_wrapper img:eq('+cnt+')').css('display') !== 'block' ) { // This prevents the current image from toggling when its thumbnail is clicked
				clearInterval(loop_interval);
				startloop(cntmax,cnt,false)
			}
		});
		
		
		// We check the thumbnails padding on load and resize
		function gallery_padding() {
			var count = $('.el_gallery-slideshow_wrapper img').length,
				window_width = $(window).width(),
				thumbs_padding = 0;
			if ( count  < 4 && window_width < 600 ) {
				padding_width = (100 - count * 20) / 2;
				thumbs_padding = padding_width + '%';
			} else if ( count  < 8 && window_width > 599 ) {
				padding_width = (100 - count * 12.5) / 2;
				thumbs_padding = padding_width + '%';
			}
			$('.el_gallery-thumbnails_wrapper').css('padding-left', thumbs_padding);
		}
		$(window).resize(function(){
			gallery_padding();
			for ( var i = 0; i < $('.attachment-thumbnail').length; i++ ) {
				if ( $('.attachment-thumbnail:eq('+i+')').hasClass('current') ) {
					var resize_cnt = i;
					break;
				}
			}
			clearInterval(loop_interval);
			startloop(cntmax,resize_cnt,"resize");
		});
		$(document).ready(function(){gallery_padding()});
	
	}
	
	
	// We preload the "loading" gif
	function preload(arrayOfImages) {
		$(arrayOfImages).each(function(index){
			$('<img />')
			.attr('src', arrayOfImages[index])
			.load(function(){
				start_slideshow();
			});
		});
	}
	preload([
		'http://ericlowry.fr/en/wp-content/uploads/sites/3/2014/05/loadingGif.gif'
	]);
});
