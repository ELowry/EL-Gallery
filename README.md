EL-Gallery
==========

Contributors: Eric Lowry<br />
Plugin Name: EL-Gallery<br />
Plugin URI: http://ericlowry.fr/en/development/#el-gallery<br />
Tags: wp, gallery, css3, jQuery, shortcode, responsive, simple<br />
Author URI: http://ericlowry.fr/<br />
Author: Eric Lowry<br />
Requires at least: 3.5<br />
Tested up to: 3.9.1<br />
Stable tag: 1.0<br />
Version: 1.0<br />
License: GPLv2<br />
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Description
-----------


EL-Gallery is an elegant untra-lightweight javascript & css gallery replacement for Wordpress.

<a href="http://ericlowry.fr/en/development/#el-gallery" title="EL-Gallery, the lightweight wordpress gallery" target="_blank">EL-Gallery</a> will simply adapt to your website’s width without any fuss or hours of configuration.<br />
A few simple options are at your disposal if you wish, but the plugin will work perfectly well as soon as it is installed.

### Details

I am not a professional developer, but there were no plugins that were both simple enough and could adapt to responsive design. This is why I chose to code this plugin.

EL-Gallery should function properly on any browser that supports both CSS3 and javascript.

If you have any suggestions or questions, please [contact me](http://ericlowry.fr/en/contact/ "Contact Eric Lowry (Plugin Author)") directly, or propose changes on [this project's GitHub page](https://github.com/ELowry/EL-Gallery "EL-Gallery on GitHub").

This plugin is designed to function on any type of machine, and for it to best function on mobile devices, we reccomend you install the [WP Mobile Detect](http://wordpress.org/plugins/wp-mobile-detect/ "Plugin : WP Mobile Detect") plugin.

EL-Gallery is currently available in both English and French.

### Configuration
Once installed, you will find the configuration menu for EL-Gallery in the “Appearance” section of  your wordpress administration page.

There you will be able to modify the duration of each slide as well as a few other functions:<br />
* EL-Gallery uses responsive design, and you may wish to choose a specific page width at which the thumbnails will go from 8 per line to 5 per line.
* You may wish to disable the links on a gallery’s images.
* If you have installed the [WP Mobile Detect](http://wordpress.org/plugins/wp-mobile-detect/ "Plugin : WP Mobile Detect") plugin, don’t forget to activate the corresponding option so images may display in a lighter format when on mobile devices.

### Examples

* http://ericlowry.fr/en/<br />
* http://alpha-gamma.fr/

Installation
------------

1. Download the plugin.
2. Simply go under the the *Plugins* menu in WordPress, then click on Add new and select the plugin's .zip file
3. Alternatively you can upload the *el-gallery* folder to the */wp-content/plugins/* directory.
4. Finally, activate the plugin through the 'Plugins' menu in WordPress.

Your gallery shortcodes will automatically be updated to EL-Galleries !

Changelog
---------


### Version 1.0
* Added option to set maximum aspect ratio (to avoid tall images being oversized).
* Added "informations" section to the customisation menu.
* Fixed donations link.

### Version 0.94
* Thumbnails only appear once the gallery is loaded.
* Fixed thumbnail centering when page is resized.
* Optimized thumbnail centering.

### Version 0.93

* Added options to center thumbnails or leave them aligned to the left.
* Fixed initial value of options not being set correctly upon activation and not being removed after deactivation.
* Fixed plugin URI (in plugins list).

### Version 0.92

* Added options menu with : slideshow speed, thumbnail number switch width, togglable image links, togglable "WP Mobile Detect" compatibility.<br />
* Fixed javascript so multiple galleries can be displayed on the same page.<br />
* Added translation system (with French translation onboard).<br />
* Optimized php (repeated elements were passed into functions).<br />

### Version 0.91

 * Images use absolute positioning, not floats (allows for smooth transitions).<br />

### Version 0.9

* Initial release.

Donations
---------

http://ericlowry.fr/en
