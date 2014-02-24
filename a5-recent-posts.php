<?php
/*
Plugin Name: A5 Recent Post Widget
Plugin URI: http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/recent-post-widget
Description: A5 Recent Posts Widget just displays the most recent post in a customizable widget. Set the colours of the links and border, show the widget on sites, that you define, ready.
Version: 2.0
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

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) die('Sorry, you don\'t have direct access to this page.');

define( 'RPW_PATH', plugin_dir_path(__FILE__) );

if (!class_exists('A5_Image')) require_once RPW_PATH.'class-lib/A5_ImageClass.php';
if (!class_exists('A5_Excerpt')) require_once RPW_PATH.'class-lib/A5_ExcerptClass.php';
if (!class_exists('A5_Recent_Post_Widget')) require_once RPW_PATH.'class-lib/RPW_WidgetClass.php';
if (!class_exists('A5_FormField')) require_once RPW_PATH.'class-lib/A5_FormFieldClass.php';
if (!class_exists('A5_OptionPage')) require_once RPW_PATH.'class-lib/A5_OptionPageClass.php';
if (!class_exists('A5_DynamicCSS')) :

	require_once RPW_PATH.'class-lib/A5_DynamicCSSClass.php';
	
	$dynamic_css = new A5_DynamicCSS;
	
endif;

class RecentPostWidget {

	const language_file = 'a5-recent-posts';
	
	private static $options;

	function __construct() {
		
		self::$options = get_option('rpw_options');
		
		register_activation_hook(  __FILE__, array($this, 'install') );
		register_deactivation_hook(  __FILE__, array($this, 'uninstall') );
		
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
		add_filter('plugin_row_meta', array($this, 'register_links'), 10, 2);
		add_filter('plugin_action_links', array($this, 'register_action_links'), 10, 2);
		
		add_action('admin_init', array($this, 'register_settings'));
		add_action('admin_menu', array($this, 'add_settings_page'));
		
		// import laguage files

		load_plugin_textdomain(self::language_file, false , basename(dirname(__FILE__)).'/languages');
		
		$eol = "\r\n";
		$tab = "\t";
		
		A5_DynamicCSS::$styles .= $eol.'/* CSS portion of the A5 Recent Post Widget */'.$eol.$eol;
		
		A5_DynamicCSS::$styles.='div[id^="a5_recent_post_widget"].widget_a5_recent_post_widget img {'.$eol.$tab.'height: auto;'.$eol.$tab.'max-width: 100%;'.$eol.'}'.$eol;
		
		A5_DynamicCSS::$styles.='div[id^="a5_recent_post_widget"].widget_a5_recent_post_widget {'.$eol.$tab.'-moz-hyphens: auto;'.$eol.$tab.'-o-hyphens: auto;'.$eol.$tab.'-webkit-hyphens: auto;'.$eol.$tab.'-ms-hyphens: auto;'.$eol.$tab.'hyphens: auto; '.$eol.'}'.$eol;
		
		if (!empty (self::$options['link'])) :
		
			$style=str_replace('; ', ';'.$eol.$tab, str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['link']));
		
			A5_DynamicCSS::$styles.='div[id^="a5_recent_post_widget"].widget_a5_recent_post_widget a {'.$eol.$tab.$style.$eol.'}'.$eol;
			
		endif;
		
		if (!empty (self::$options['hover'])) :
		
			$style=str_replace('; ', ';'.$eol.$tab, str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['hover']));
		
			A5_DynamicCSS::$styles.='div[id^="a5_recent_post_widget"].widget_a5_recent_post_widget a:hover {'.$eol.$tab.$style.$eol.'}'.$eol;
			
		endif;
		
	}

	/* attach JavaScript file for textarea resizing */
	
	function enqueue_scripts($hook) {
		
		if ($hook != 'widgets.php' && $hook != 'settings_page_a5-recent-posts-settings') return;
		
		wp_register_script('ta-expander-script', plugins_url('ta-expander.js', __FILE__), array('jquery'), '3.0', true);
		wp_enqueue_script('ta-expander-script');
	
	}
	
	//Additional links on the plugin page
	
	function register_links($links, $file) {
		
		$base = plugin_basename(__FILE__);
		if ($file == $base) {
			$links[] = '<a href="http://wordpress.org/extend/plugins/advanced-category-column/faq/" target="_blank">'.__('FAQ', self::language_file).'</a>';
			$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YGA57UKZQVP4A" target="_blank">'.__('Donate', self::language_file).'</a>';
		}
		
		return $links;
	}
	
	function register_action_links( $links, $file ) {
		
		$base = plugin_basename(__FILE__);
		
		if ($file == $base) array_unshift($links, '<a href="'.admin_url( 'options-general.php?page=a5-recent-posts-settings' ).'">'.__('Settings', self::language_file).'</a>');
	
		return $links;
	
	}
	
	// init
	
	function register_settings() {
		
		register_setting( 'rpw_options', 'rpw_options', array($this, 'validate') );
		
		add_settings_section('rpw_settings', __('Styling of the links', self::language_file), array($this, 'display_section'), 'rpw_styles');
		
		add_settings_field('rpw_link_style', __('Link style:', self::language_file), array($this, 'link_field'), 'rpw_styles', 'rpw_settings');
		
		add_settings_field('rpw_hover_style', __('Hover style:', self::language_file), array($this, 'hover_field'), 'rpw_styles', 'rpw_settings');
		
		add_settings_field('rpw_resize', false, array($this, 'resize_field'), 'rpw_styles', 'rpw_settings');
	
	}
	
	function display_section() {
		
		echo '<p>'.__('Just put some css code here.', self::language_file).'</p>';
	
	}
	
	function link_field() {
		
		a5_textarea('link', 'rpw_options[link]', @self::$options['link'], false, array('cols' => 35, 'rows' => 3));
		
	}
	
	function hover_field() {
		
		a5_textarea('hover', 'rpw_options[hover]', @self::$options['hover'], false, array('cols' => 35, 'rows' => 3));
		
	}
	
	function resize_field() {
		
		a5_resize_textarea(array('link', 'hover'), true);
		
	}
	
	// Creating default options on activation
	
	function install() {
		
		$default = array(
			'tags' => array(),
			'sizes' => array()
		);
		
		add_option('rpw_options', $default);
		
	}	
	
	// Cleaning on deactivation
	
	function uninstall() {
		
		delete_option('rpw_options');
		
	}
	
	// Installing options page
	
	function add_settings_page() {
		
		$pages=add_options_page('A5 Recent Post '.__('Settings', self::language_file), '<img alt="" src="'.plugins_url('a5-recent-posts/img/a5-icon-11.png').'"> A5 Recent Posts', 'administrator', 'a5-recent-posts-settings', array($this, 'build_options_page'));
		
	}
	
	// Calling the options page
	
	function build_options_page() {
		
		A5_OptionPage::open_page('A5 Recent Post Widget', __('http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/recent-post-widget', self::language_file), 'a5-recent-posts', __('Plugin Support', self::language_file));
		
		_e('Style the links of the widget. If you leave this empty, your theme will style the hyperlinks.', self::language_file); ?>
		<p><?php _e('Just input something like,', self::language_file); ?></p>
		<p><strong>font-weight: bold;<br />
		color: #0000ff;<br />
		text-decoration: underline;    
		</strong></p>
		<?php _e('to get fat, blue, underlined links.', self::language_file); ?></p>
		<p><strong><?php _e('You most probably have to use &#34;!important&#34; at the end of each line, to make it work.', self::language_file); ?></strong></p>
		<?php
		
		A5_OptionPage::open_form('options.php');
		
		settings_fields('rpw_options');
		do_settings_sections('rpw_styles');
		submit_button();
		
		A5_OptionPage::close_page();
		
	}
	
	function validate($input) {
		
		self::$options['link']=trim($input['link']);
		self::$options['hover']=trim($input['hover']);
		
		return self::$options;
	
	}

} // end of class

$a5_recent_post_widget = new RecentPostWidget;

?>