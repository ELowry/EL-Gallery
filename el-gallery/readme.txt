﻿=== EL-Gallery ===

Contributors: Eric Lowry
Plugin Name: EL-Gallery
Plugin URI: http://ericlowry.fr/en/development/#el-gallery
Tags: wp, gallery, css3, jQuery, shortcode, responsive, simple, fontawsome
Author URI: http://ericlowry.fr/
Author: Eric Lowry
Requires at least: 3.5
Tested up to: 4.7.2
Stable tag: 1.5
Version: 1.5
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html


EL-Gallery is an elegant untra-lightweight javascript & css gallery replacement for Wordpress.


== Description ==

[EL-Gallery](http://ericlowry.fr/en/development/#el-gallery "EL-Gallery, the lightweight wordpress gallery") will simply adapt to your website’s width without any fuss or hours of configuration.

A few simple options are at your disposal if you wish, but the plugin will work perfectly well as soon as it is installed.

= Details =
I am not a professional developer, but there were no plugins that were both simple enough and could adapt to responsive design. This is why I chose to code this plugin.

EL-Gallery should function properly on any browser that supports both CSS3 and javascript.

If you have any suggestions or questions, please [contact me](http://ericlowry.fr/en/contact/ "Contact Eric Lowry (Plugin Author)") directly, or propose changes on [this project's GitHub page](https://github.com/ELowry/EL-Gallery "EL-Gallery on GitHub").

This plugin is designed to function on any type of machine, and for it to best function on mobile devices, we reccomend you install the [WP Mobile Detect](http://wordpress.org/plugins/wp-mobile-detect/ "Plugin : WP Mobile Detect") plugin.

EL-Gallery is currently available in both English and French. If you wish to help translate it into another language, please [contact me](http://ericlowry.fr/en/contact/ "Contact Eric Lowry (Plugin Author)").

= Configuration =
Once installed, you will find the configuration menu for EL-Gallery in the “Appearance” section of  your wordpress administration page.

There you will be able to modify the duration of each slide as well as a few other functions:

* EL-Gallery uses responsive design, and you may wish to choose a specific page width at which the thumbnails will go from 8 per line to 5 per line.
* You may wish to disable the links on a gallery’s images.
* If you have installed the [WP Mobile Detect](http://wordpress.org/plugins/wp-mobile-detect/ "Plugin : WP Mobile Detect") plugin, don’t forget to activate the corresponding option so images may display in a lighter format when on mobile devices.
* You can use the following [shortcode parameters](https://codex.wordpress.org/Shortcode_API#Overview "About Shortcode Attributes"):
  * _nopause="1"_ to remove the pause button and autoplay functionality
  * _nothumb="1"_ to remove the thumbnail display.

= Examples =

* http://ericlowry.fr/en/
* http://alpha-gamma.fr/

= Contributors =

* Andrew Kurtis for [WebHostingHub](http://webhostinghub.com).

== Installation ==

1. Download the plugin.
2. Simply go under the the *Plugins* menu in WordPress, then click on Add new and select the plugin's .zip file
3. Alternatively you can upload the *el-gallery* folder to the */wp-content/plugins/* directory.
4. Finally, activate the plugin through the 'Plugins' menu in WordPress.

Your gallery shortcodes will automatically be updated to EL-Galleries !

Note : If your theme or other plugins cause incompatibility issues (mainly overriding EL-Gallery), simply change any [gallery] shortcode to [el-gallery].

== Screenshots ==

1. This is an example of an EL-Gallery as seen on [My Portfolio](http://ericlowry.fr/en/ "ericlowry.fr").
2. This is the complete list of options for EL-Gallery.
3. This is an example of an EL-Gallery as seen on a mobile phone.

== Changelog ==

= Version 1.5 =
* Added shortcode parameter _nopause="1"_ to remove the pause button and autoplay functionality.
* Added shortcode parameter _nothumb="1"_ to remove the thumbnail display.

= Version 1.4.2 =
* Added an alternative shortcode [el-gallery].

= Version 1.4.1 =
* Small update to loading-icons.

= Version 1.4 =
* Default image size is set to large. Use [shortcode settings](http://codex.wordpress.org/Gallery_Shortcode#Options "Wordpress Shortcode Options") to modify the image size for each gallery.
* Smooth height transitions have been added (delays still need tweaking).
* Various display bugs have been fixed.

= Version 1.3 =
* The gallery no-longer changes slides if they are out of view (avoids superfluous page mouvement).
* The pause button stays active when slides are manually changed.

= Version 1.2.9 =
* Added Spanish translation.

= Version 1.2.8 =
* Fixed major bug with javascript loading.
* Minor bugfixes.

= Version 1.2.7 =
* Created custom icon font with more loading icons and lighter files.

= Version 1.2.6a =
* Fixed error in FontAwsome.

= Version 1.2.6 =
* New color selection system on most browsers.
* Minor bugfixes.
* Fixed translations.

= Version 1.2.5 =
* Simpler admin interface.
* More Font Awsome loading icon.d
* Various color corrections.

= Version 1.2.4 =
* Navigation buttons display only if loaded.
* Shortcode "ratio" has been added to modify the image's hight limitation ratio.

= Version 1.2.3 =
* Added a play/pause button.
* Fixed the image link system.

= Version 1.2.2 =
* Fixed a major bug from previous version.
* Added a choice of three loading icons.

= Version 1.2.1 =
* Fixed an eventual bug linked to using WP Mobile Detect.

= Version 1.2 =
* Added Font Awsome 4.2 support.
* Added a new Font Awsome loading icon.

= Version 1.1 =
* Added navigation arrows.
* Added option to change background-color for navigation arrows.
* Added option to use white navigation arrows (for dark backgrounds).

= Version 1.01 =
* PHP optimisation.

= Version 1.0 =
* Added option to set maximum aspect ratio (to avoid tall images being oversized).
* Added "informations" section to the customisation menu.
* Fixed donations link.

= Version 0.94 =
* Thumbnails only appear once the gallery is loaded.
* Fixed thumbnail centering when page is resized.
* Optimized thumbnail centering.

= Version 0.93 =
* Added options to center thumbnails or leave them aligned to the left.
* Fixed initial value of options not being set correctly upon activation and not being removed after deactivation.
* Fixed plugin URI (in plugins list).

= Version 0.92 =
* Added options menu with : slideshow speed, thumbnail number switch width, togglable image links, togglable "WP Mobile Detect" compatibility.
* Fixed javascript so multiple galleries can be displayed on the same page.
* Added translation system (with French translation onboard).
* Optimized php (repeated elements were passed into functions).

= Version 0.91 =
* Images use absolute positioning, not floats (allows for smooth transitions).

= Version 0.9 =
* Initial release.

== Donations ==

http://ericlowry.fr/en/development/#el-gallery (donate button to the right)