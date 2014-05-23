<?php
/*
Plugin Name: A5 Recent Post Widget
Plugin URI: http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/recent-post-widget
Description: A5 Recent Posts Widget just displays the most recent post in a customizable widget. Set the colours of the links and border, show the widget on sites, that you define, ready.
Version: 2.4.2
Author: Waldemar Stoffel
Author URI: http://www.waldemarstoffel.com
License: GPL3
Text Domain: a5-recent-posts
*/

/*  Copyright 2012 - 2014  Waldemar Stoffel  (email : stoffel@atelier-fuenf.de)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/


/* Stop direct call */

defined('ABSPATH') OR exit;

if (!defined('RPW_PATH')) define( 'RPW_PATH', plugin_dir_path(__FILE__) );
if (!defined('RPW_BASE')) define( 'RPW_BASE', plugin_basename(__FILE__) );

# loading the framework
if (!class_exists('A5_Image')) require_once RPW_PATH.'class-lib/A5_ImageClass.php';
if (!class_exists('A5_Excerpt')) require_once RPW_PATH.'class-lib/A5_ExcerptClass.php';
if (!class_exists('A5_FormField')) require_once RPW_PATH.'class-lib/A5_FormFieldClass.php';
if (!class_exists('A5_OptionPage')) require_once RPW_PATH.'class-lib/A5_OptionPageClass.php';
if (!class_exists('A5_DynamicFiles')) require_once RPW_PATH.'class-lib/A5_DynamicFileClass.php';

#loading plugin specific classes
if (!class_exists('RPW_Admin')) require_once RPW_PATH.'class-lib/RPW_AdminClass.php';
if (!class_exists('RPW_DynamicCSS')) require_once RPW_PATH.'class-lib/RPW_DynamicCSSClass.php';
if (!class_exists('A5_Recent_Post_Widget')) require_once RPW_PATH.'class-lib/RPW_WidgetClass.php';

class RecentPostWidget {

	const language_file = 'a5-recent-posts';
	
	private static $options;

	function __construct() {
		
		self::$options = get_option('rpw_options');
		
		if (isset(self::$options['tags'])) $this->update_plugin_options();
		
		register_activation_hook(  __FILE__, array(&$this, '_install') );
		register_deactivation_hook(  __FILE__, array(&$this, '_uninstall') );
		
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts'));
		add_filter('plugin_row_meta', array(&$this, 'register_links'), 10, 2);
		add_filter('plugin_action_links', array(&$this, 'register_action_links'), 10, 2);
		
		// import laguage files

		load_plugin_textdomain(self::language_file, false , basename(dirname(__FILE__)).'/languages');
		
		$RPW_DynamicCSS = new RPW_DynamicCSS;
		$RPW_Admin = new RPW_Admin;
		
	}

	/* attach JavaScript file for textarea resizing */
	
	function enqueue_scripts($hook) {
		
		if ($hook != 'widgets.php' && $hook != 'settings_page_a5-recent-posts-settings') return;
		
		wp_register_script('ta-expander-script', plugins_url('ta-expander.js', __FILE__), array('jquery'), '3.0', true);
		wp_enqueue_script('ta-expander-script');
	
	}
	
	//Additional links on the plugin page
	
	function register_links($links, $file) {
		
		if ($file == RPW_BASE) {
			$links[] = '<a href="http://wordpress.org/extend/plugins/a5-recent-posts/faq/" target="_blank">'.__('FAQ', self::language_file).'</a>';
			$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YGA57UKZQVP4A" target="_blank">'.__('Donate', self::language_file).'</a>';
		}
		
		return $links;
	}
	
	function register_action_links( $links, $file ) {
		
		if ($file == RPW_BASE) array_unshift($links, '<a href="'.admin_url( 'options-general.php?page=a5-recent-posts-settings' ).'">'.__('Settings', self::language_file).'</a>');
	
		return $links;
	
	}
	
	// Creating default options on activation
	
	function _install() {
		
		$default = array(
			'cache' => array(),
			'inline' => false
		);
		
		add_option('rpw_options', $default);
		
	}	
	
	// Cleaning on deactivation
	
	function _uninstall() {
		
		delete_option('rpw_options');
		
	}
	
	// updating options in case they are outdated
	
	function _update_plugin_options() {	
		
		self::$options['cache'] = array();
		
		self::$options['inline'] = false;
		
		unset(self::$options['tags'], self::$options['sizes']);
		
		update_option('rpw_options', self::$options);
	
	}

} // end of class

$A5_Recent_Post_Widget = new RecentPostWidget;

?>