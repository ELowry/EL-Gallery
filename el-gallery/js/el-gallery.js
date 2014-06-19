/*
Plugin Name: EL-Gallery
Plugin URI: http://ericlowry.fr/
Description: An extremely simplistic gallery replacement plugin.
Version: 0.93
Author: Eric Lowry
Author URI: http://ericlowry.fr/
License: GPL2
*/

( function($, data){

	var duration = data.duration ;
	var switch_width = data.switch_width ;
	var centered = data.centered ;
	
	function variable_css(switch_width){
		if ($(window).width() < switch_width ) {
			$('figure.el_gallery figcaption.el_gallery-thumbnails_wrapper img').css({'width': '16%', 'margin': '0 2%'});
		} else {
			$('figure.el_gallery figcaption.el_gallery-thumbnails_wrapper img').css({'width': '10%', 'margin': '0 1.25%'});
		}
	}
	$(window).resize(function(){variable_css(switch_width)});
	$(document).ready(function(){variable_css(switch_width)});

	var gallery_number = 0;
	$('.el_gallery').each(function(){
		$(this).addClass('el_gallery_number-' + gallery_number );


		var curr_gallery = '.el_gallery_number-' + gallery_number;


		function start_slideshow(duration,centered,curr_gallery) {

			// This function is the slideshow loop itsself
			function startloop(cntmax,cnt,type,duration,curr_gallery) {
				// We display the first/selected image correctly
				if ( type == false ) {
					$('.el_gallery-thumbnails_wrapper img',curr_gallery).removeClass('current');
					$('.el_gallery-thumbnails_wrapper img:eq('+cnt+')',curr_gallery).addClass('current');
					$('.el_gallery-slideshow_wrapper img',curr_gallery).css('z-index','5');
					$('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).css('z-index','6');
					$('.el_gallery-slideshow_wrapper img',curr_gallery).fadeOut('slow');
					$('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).fadeIn('slow');
				}
				if ( type != "first" ) {
					var gallery_height = $('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).height();
					$('.el_gallery-slideshow_wrapper',curr_gallery).css('height', gallery_height);
					// We set up the interval
					clearInterval(loop_interval);
					loop_interval = setInterval(execute_loop,duration,curr_gallery); // Change number to make the slides last more/less
				} else {
					// We wait for the first image to be loaded
					var image = new Image();
					image.onload = function () {
						setTimeout(function(){
							$('.el_gallery-slideshow_wrapper',curr_gallery).css('background-image','none');
							startloop(cntmax,cnt,false,duration,curr_gallery);
						}, 1000); // the image has loaded, we display it
					}
					image.onerror = function () {
					   console.error("Cannot load image");
					}
					image.src = $('.el_gallery-thumbnails_wrapper img:eq(0)',curr_gallery).attr('src');
				}
				function execute_loop(curr_gallery) {
					cnt == cntmax ? cnt=0:cnt++
					$('.el_gallery-thumbnails_wrapper img',curr_gallery).removeClass('current');
					$('.el_gallery-thumbnails_wrapper img:eq('+cnt+')',curr_gallery).addClass('current');
					$('.el_gallery-slideshow_wrapper img',curr_gallery).css('z-index','5');
					$('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).css('z-index','6');
					$('.el_gallery-slideshow_wrapper img',curr_gallery).fadeOut('slow');
					$('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).fadeIn('slow');
					var gallery_height = $('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).height();
					$('.el_gallery-slideshow_wrapper',curr_gallery).animate({'height': gallery_height}, 500);
				}
			}

			// We give the thumbnails a numbered id
			$(".el_gallery-thumbnails_wrapper img",curr_gallery).each(function(index){
				var thumb_id = 'el_gallery-thumbnails-' + index;
				$(this).attr('id', thumb_id);
			});

			var cntmax = $('.el_gallery-slideshow_wrapper img',curr_gallery).length - 1, // We check out how many images there are
				cnt = 0, // We start with the first image
				loop_interval = null;
			startloop(cntmax,cnt,"first",duration,curr_gallery); // We load the slideshow loop for the first time

			// We setup the "skip-to" functions on thumbnails
			$(".el_gallery-thumbnails_wrapper img",curr_gallery).bind("click", function(event) {
				cntmax = $('.el_gallery-slideshow_wrapper img',curr_gallery).length - 1;
				var id = $(this).attr('id');
				id = id.substr(id.length - 2);
				cnt = id.replace(/^-+/i, '');
				if ( $('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).css('display') !== 'block' ) { // This prevents the current image from toggling when its thumbnail is clicked
					clearInterval(loop_interval);
					startloop(cntmax,cnt,false,duration,curr_gallery)
				}
			});



			// We check the thumbnails padding on load and resize
			function gallery_padding(switch_width,curr_gallery) {
				var count = $('.el_gallery-slideshow_wrapper img',curr_gallery).length,
					window_width = $(window).width(),
					thumbs_padding = 0;
				if ( count  < 5 && window_width < switch_width ) {
					padding_width = (100 - count * 20) / 2;
					thumbs_padding = padding_width + '%';
				} else if ( count  < 8 && window_width > ( switch_width - 1 ) ) {
					padding_width = (100 - count * 12.5) / 2;
					thumbs_padding = padding_width + '%';
				}
				$('.el_gallery-thumbnails_wrapper',curr_gallery).css('padding-left', thumbs_padding);
			}
			// We set up the function to center extra thumbnails
			function center_thumbnails(curr_gallery) {
				var count = count = $('.el_gallery-slideshow_wrapper img',curr_gallery).length,
				window_width = $(window).width();
				if ( count  > 5 && window_width < switch_width ) {
					num = 5;
				} else if ( count  > 8 && window_width > ( switch_width - 1 ) ) {
					num = 8;
				}
				var full_lines = Math.floor(count / num),
					count_extra = ((count / num) - full_lines) * num,
					curr_count = 0;
				$('.el_gallery-thumbnails_wrapper img',curr_gallery).each(function() {
					if (curr_count == (full_lines * num)) {
						var thumb_margin_num = 1.25 + ((100 - (count_extra * 12.5)) / 2);
						var thumb_margin = thumb_margin_num + "%";
						$(this).css('margin-left', thumb_margin);
						return false;
					}
					curr_count++;
				});
			}


			$(window).resize(function(){
				if (centered == "true") {
					gallery_padding(curr_gallery);
					center_thumbnails(curr_gallery);
				}
				for ( var i = 0; i < $('.attachment-thumbnail',curr_gallery).length; i++ ) {
					if ( $('.attachment-thumbnail:eq('+i+')',curr_gallery).hasClass('current') ) {
						var resize_cnt = i;
						break;
					}
				}
				clearInterval(loop_interval);
				startloop(cntmax,resize_cnt,"resize",duration,curr_gallery);
			});

			$(document).ready(function(){
				if (centered == "true") {
					gallery_padding(switch_width,curr_gallery);
					center_thumbnails(curr_gallery);
				}
			});

		}


		// We preload the "loading" gif
		function preload(arrayOfImages,duration,curr_gallery) {
			$(arrayOfImages).each(function(index){
				$('<img />')
				.attr('src', arrayOfImages[index])
				.load(function(){
					start_slideshow(duration,centered,curr_gallery);
				});
			});
		}
		preload([
			'http://ericlowry.fr/en/wp-content/uploads/sites/3/2014/05/loadingGif.gif'
		],duration,curr_gallery);


		gallery_number++;

	});

})( jQuery, el_gallery_parameters );