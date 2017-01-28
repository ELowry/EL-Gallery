/*
Plugin Name: EL-Gallery
Description: An extremely simplistic gallery replacement plugin.
Version: 1.5
Author: Eric Lowry
Author URI: http://ericlowry.fr/
License: GPL2
*/

( function($, data){

	$(document).ready(function() {
	
		var duration = data.duration,
			switch_width = data.switch_width,
			max_height = data.max_height,
			nav = data.nav,
			nav_color = data.nav_color,
			centered = data.centered
			no_pause = data.no_pause;

		function convertHex(hex,opacity){
			hex = hex.replace('#','');
			if (hex.length < 6) {
				hex = hex.substring(0,1) + hex.substring(0,1) + hex.substring(1,2) + hex.substring(1,2) + hex.substring(2,3) + hex.substring(2,3);
			}
			r = parseInt(hex.substring(0,2), 16);
			g = parseInt(hex.substring(2,4), 16);
			b = parseInt(hex.substring(4,6), 16);

			result = 'rgba('+r+','+g+','+b+','+opacity/100+')';
			return result;
		}

		function variable_css(switch_width){ // This sets up the swich-width option
			if ($(window).width() < switch_width ) {
				$('figure.el_gallery figcaption.el_gallery-thumbnails_wrapper img').css({'width': '16%', 'margin': '2%'});
			} else {
				$('figure.el_gallery figcaption.el_gallery-thumbnails_wrapper img').css({'width': '10%', 'margin': '1.25%'});
			}
		}
		$(window).resize(function(){variable_css(switch_width)});
		$(document).ready(function(){variable_css(switch_width)});

		var gallery_number = 0;
		$('.el_gallery').each(function(){
			$(this).addClass('el_gallery_number-' + gallery_number );


			var curr_gallery = '.el_gallery_number-' + gallery_number;


			function start_slideshow(duration,centered,nav,nav_color,max_height,curr_gallery) {

				// This function is the slideshow loop itsself
				function startloop(cntmax,cnt,type,duration,max_height,curr_gallery) {
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
						if ( max_height != "" ) {
							$('.el_gallery-slideshow_wrapper img',curr_gallery).each(function(){
								var image_height_limit = $('.el_gallery-slideshow_wrapper',curr_gallery).width() * max_height;
								if ( $(this).height() > image_height_limit ) {
									$(this).css({'height': image_height_limit, 'width': 'auto'});
									$(this).addClass('el-tall');
									var image_padding = ( $('.el_gallery-slideshow_wrapper',curr_gallery).width() - $(this).width() ) / 2;
									$(this).css('padding-left', image_padding);
								};
							});
						};
						var gallery_height = $('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).height();
						$('.el_gallery-slideshow_wrapper',curr_gallery).css('height', gallery_height);
						// We set up the interval
						if (!no_pause) {
							clearInterval(loop_interval);
							loop_interval = setInterval(execute_loop,duration,curr_gallery); // Call the rotation of slides
						}

					} else {
						// We wait for the first image to be loaded
						var image = new Image();
						image.onload = function () {
							setTimeout(function(){
								$('.el_gallery-slideshow_wrapper .el_loading',curr_gallery).css('display','none');
								if (nav == 'true') {
									$('.el_nav',curr_gallery).removeClass('loading');
								}
								$('.el_pause',curr_gallery).attr('style','');
								$('.el_gallery-thumbnails_wrapper img',curr_gallery).css('height', 'auto');
								startloop(cntmax,cnt,false,duration,max_height,curr_gallery);
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
				startloop(cntmax,cnt,"first",duration,max_height,curr_gallery); // We load the slideshow loop for the first time

				// We setup the "skip-to" functions on thumbnails
				$(".el_gallery-thumbnails_wrapper img",curr_gallery).bind("click", function(e) {
					e.preventDefault();
					cntmax = $('.el_gallery-slideshow_wrapper img',curr_gallery).length - 1;
					var id = $(this).attr('id');
					id = id.substr(id.length - 2);
					cnt = id.replace(/^-+/i, '');
					if ( $('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).css('display') !== 'block' ) { // This prevents the current image from toggling when its thumbnail is clicked
						if (paused == false) {
							clearInterval(loop_interval);
							startloop(cntmax,cnt,false,duration,max_height,curr_gallery);
						} else {
							startloop(cntmax,cnt,false,duration,max_height,curr_gallery);
							clearInterval(loop_interval);
						}
					}
				});

				// We color the loading button's hole
				if (typeof nav_color != 'undefined' && nav_color != '') {
				}

				// We setup the nav section
				if (nav == 'true') {
					$('.el_nav',curr_gallery).css('display', 'block').addClass('loading');
					if (typeof nav_color != 'undefined') {
						var left_gradient = 'linear-gradient(to left, ' + convertHex(nav_color,0) + ', #' + nav_color.replace('#','') + ')',
							right_gradient = 'linear-gradient(to right, ' + convertHex(nav_color,0).replace('#','') + ', #' + nav_color.replace('#','') + ')',
							hole_color = '#' + nav_color.replace('#','');
						$('.el_nav-left',curr_gallery).css( 'background', left_gradient);
						$('.el_nav-right',curr_gallery).css( 'background', right_gradient);
						$('.el-stack .el-icons-stack-1x',curr_gallery).css( 'color', hole_color);
					}
					$('.el_nav-left', curr_gallery).bind("click", function(e) { // Left Arrow
						e.preventDefault();
						cntmax = $('.el_gallery-slideshow_wrapper img',curr_gallery).length - 1;
						var id = $('.el_gallery-thumbnails_wrapper img.current',curr_gallery).attr('id');
						id = id.substr(id.length - 2);
						cnt = id.replace(/^-+/i, '') - 1;
						if (cnt == -1) {
							cnt = cntmax;
						}
						if ( $('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).css('display') !== 'block' ) { // This prevents the current image from toggling when its thumbnail is clicked
							if (paused == false) {
								clearInterval(loop_interval);
								startloop(cntmax,cnt,false,duration,max_height,curr_gallery);
							} else {
								startloop(cntmax,cnt,false,duration,max_height,curr_gallery);
								clearInterval(loop_interval);
							}
						}
					});
					$('.el_nav-right',curr_gallery).bind("click", function(e) { // Right Arrow
						e.preventDefault();
						cntmax = $('.el_gallery-slideshow_wrapper img',curr_gallery).length - 1;
						var id = $('.el_gallery-thumbnails_wrapper img.current',curr_gallery).attr('id');
						id = id.substr(id.length - 2);
						cnt = parseInt(id.replace(/^-+/i, '')) + 1;
						if (cnt > cntmax) {
							cnt = 0;
						}
						if ( $('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).css('display') !== 'block' ) { // This prevents the current image from toggling when its thumbnail is clicked
							if (paused == false) {
								clearInterval(loop_interval);
								startloop(cntmax,cnt,false,duration,max_height,curr_gallery);
							} else {
								startloop(cntmax,cnt,false,duration,max_height,curr_gallery);
								clearInterval(loop_interval);
							}
						}
					});

				// We set up the Pause button
					if (!no_pause) {
						var paused = false;
						$('.el_pause',curr_gallery).bind({"mouseenter mouseleave": function() {
							if (paused == true) {
								$('i.el-icons',this).toggleClass("el-icons-pause el-icons-play");
							}
						}, "click": function(e) {
							e.preventDefault();
							if (paused == true) {
								$(this).attr('style','');
								$('i.el-icons div',this).html('Pause');
								startloop(cntmax,cnt,true,duration,max_height,curr_gallery)
								paused = false;
							} else {
								$(this).css('display','block');
								$('i.el-icons div',this).html('Play');
								clearInterval(loop_interval);
								paused = true;
							}
							$('i.el-icons',this).toggleClass("el-icons-pause el-icons-play");
						}});
					}

				}


				// This temporarily pauses the gallery when it is not on screen
				if (!no_pause) {
					var overflow = false;
					function pause_overflow(){
						var win_top = $(window).scrollTop(),
							win_bottom = $(window).scrollTop() + $(window).height(),
							gallery_top = $('.el_gallery-slideshow_wrapper',curr_gallery).offset().top,
							gallery_bottom = $('.el_gallery-slideshow_wrapper',curr_gallery).offset().top + $('.el_gallery-slideshow_wrapper img:eq('+cnt+')',curr_gallery).height();
						if (overflow == true && paused == false && win_top < gallery_bottom && gallery_top < win_bottom){
							startloop(cntmax,cnt,true,duration,max_height,curr_gallery);
							overflow = false;
						} else if (overflow == false && paused == false && (win_top > gallery_bottom || gallery_top > win_bottom)){
							clearInterval(loop_interval);
							overflow = true;
						}
					}
					$(window).resize(function(){pause_overflow()});
					$(window).scroll(function(){pause_overflow()});
					$(document).ready(function(){
						setTimeout(function(){
							pause_overflow();
						}, duration / 1.5);
					});
				}


				// We set up the function to center extra thumbnails
				function center_thumbnails(curr_gallery) {
					var count = $('.el_gallery-slideshow_wrapper img',curr_gallery).length,
					window_width = $(window).width();
					if ( window_width < switch_width ) {
						var num = 5,
							other_num = 8,
							thumb_elem_width = 20;
					} else {
						var num = 8,
							other_num = 5,
							thumb_elem_width = 12.25;
					}
					var full_lines = Math.floor(count / num),
						count_extra = ((count / num) - full_lines) * num,
						other_full_lines = Math.floor(count / other_num),
						curr_count = 0;
					$('.el_gallery-thumbnails_wrapper img',curr_gallery).each(function() {
						if (curr_count == (other_full_lines * other_num)) {
							$(this).css('margin-left', '1.25%');
						}if (curr_count == (full_lines * num)) {
							var thumb_margin_num = 1.25 + ((100 - (count_extra * thumb_elem_width)) / 2);
							var thumb_margin = thumb_margin_num + "%";
							$(this).css('margin-left', thumb_margin);
						}
						curr_count++;
					});
				}


				$(window).resize(function(){
					if (centered == "true") {
						center_thumbnails(curr_gallery);
					}
					for ( var i = 0; i < $('.attachment-thumbnail',curr_gallery).length; i++ ) {
						if ( $('.attachment-thumbnail:eq('+i+')',curr_gallery).hasClass('current') ) {
							var resize_cnt = i;
							break;
						}
					}
					$('.el_gallery-slideshow_wrapper img',curr_gallery).each(function(){
						if ( $(this).hasClass( 'el-tall' ) ) {
							var image_height_limit = $('.el_gallery-slideshow_wrapper',curr_gallery).width() * max_height;
							$(this).css('height', image_height_limit);
							var image_padding = ( $('.el_gallery-slideshow_wrapper',curr_gallery).width() - $(this).width() ) / 2;
							$(this).css('padding-left', image_padding);
						};
					});
					clearInterval(loop_interval);
					startloop(cntmax,resize_cnt,"resize",duration,max_height,curr_gallery);
				});

				$(document).ready(function(){
					if (centered == "true") {
						$('.el_gallery-thumbnails_wrapper',curr_gallery).css('padding-left', '0');
						center_thumbnails(curr_gallery);
					}
				});

			}


			// We start the slideshow
			start_slideshow(duration,centered,nav,nav_color,max_height,curr_gallery);


			gallery_number++;

		});

	});
	
})( jQuery, el_gallery_parameters );