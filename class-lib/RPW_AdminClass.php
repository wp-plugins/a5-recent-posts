<?php

/**
 *
 * Class Recent Post Widget Admin
 *
 * @ A5 Recent Post Widget
 *
 * building admin page
 *
 */
class RPW_Admin extends A5_OptionPage {
	
	const language_file = 'a5-recent-posts';
	
	static $options;
	
	function __construct() {
	
		add_action('admin_init', array(&$this, 'initialize_settings'));
		add_action('admin_menu', array(&$this, 'add_admin_menu'));
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts'));
		
		self::$options = get_option('rpw_options');
		
	}
	
	/**
	 *
	 * Make debug info collapsable
	 *
	 */
	function enqueue_scripts($hook){
		
		if ('settings_page_a5-recent-posts-settings' != $hook) return;
		
		wp_enqueue_script('dashboard');
		
	}
	
	/**
	 *
	 * Add options-page for single site
	 *
	 */
	function add_admin_menu() {
		
		add_options_page('A5 Recent Post '.__('Settings', self::language_file), '<img alt="" src="'.plugins_url('a5-recent-posts/img/a5-icon-11.png').'"> A5 Recent Posts', 'administrator', 'a5-recent-posts-settings', array(&$this, 'build_options_page'));
		
	}
	
	/**
	 *
	 * Actually build the option pages
	 *
	 */
	function build_options_page() {
		
		parent::open_page('A5 Recent Post Widget', __('http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/recent-post-widget', self::language_file), 'a5-recent-posts', __('Plugin Support', self::language_file));
		
		_e('Style the links of the widget. If you leave this empty, your theme will style the hyperlinks.', self::language_file); ?>
		<p><?php _e('Just input something like,', self::language_file); ?></p>
		<p><strong>font-weight: bold;<br />
		color: #0000ff;<br />
		text-decoration: underline;    
		</strong></p>
		<?php _e('to get fat, blue, underlined links.', self::language_file); ?></p>
		<p><strong><?php _e('You most probably have to use &#39;!important&#39; at the end of each line, to make it work.', self::language_file); ?></strong></p>
		<?php
		
		parent::open_form('options.php');
		
		settings_fields('rpw_options');
		do_settings_sections('rpw_styles');
		submit_button();
		
		if (WP_DEBUG === true) :
			
			echo '<div id="poststuff">';
			
			parent::open_draggable(__('Debug Info', self::language_file), 'debug-info');
			
			echo '<pre>';
			
			var_dump(self::$options);
			
			echo '</pre>';
			
			parent::close_draggable();
			
			echo '</div>';
		
		endif;
		
		parent::close_page();
		
	}
	
	/**
	 *
	 * Initialize the admin screen of the plugin
	 *
	 */
	function initialize_settings() {
		
		register_setting( 'rpw_options', 'rpw_options', array(&$this, 'validate') );
		
		add_settings_section('rpw_settings', __('Styling of the links', self::language_file), array(&$this, 'display_section'), 'rpw_styles');
		
		add_settings_field('rpw_link_style', __('Link style:', self::language_file), array(&$this, 'link_field'), 'rpw_styles', 'rpw_settings');
		
		add_settings_field('rpw_hover_style', __('Hover style:', self::language_file), array(&$this, 'hover_field'), 'rpw_styles', 'rpw_settings');
		
		add_settings_field('use_own_css', __('Widget container:', self::language_file), array(&$this, 'rpw_display_css'), 'rpw_styles', 'rpw_settings', array(__('You can enter your own style for the widgets here. This will overwrite the styles of your theme.', self::language_file), __('If you leave this empty, you can still style every instance of the widget individually.', self::language_file)));
		
		add_settings_field('rpw_compress', __('Compress Style Sheet:', self::language_file), array(&$this, 'compress_field'), 'rpw_styles', 'rpw_settings', array(__('Click here to compress the style sheet.', self::language_file)));
		
		add_settings_field('rpw_inline', __('Debug:', self::language_file), array(&$this, 'inline_field'), 'rpw_styles', 'rpw_settings', array(__('If you can&#39;t reach the dynamical style sheet, you&#39;ll have to diplay the styles inline. By clicking here you can do so. (It might be also much faster in some environments).', self::language_file)));
		
		if (self::$options['inline']) add_settings_field('rpw_priority', __('Priority of the inline style:', self::language_file), array(&$this, 'priority_field'), 'rpw_styles', 'rpw_settings', array(__('This only affects inline styles. Some other plugins could be using the same selectors as this one. In that case, writing your&#39;s later in the code might help.', self::language_file)));
		
		$cachesize = count(self::$options['cache']);
		
		$entry = ($cachesize > 1) ? __('entries', self::language_file) : __('entry', self::language_file);
		
		if ($cachesize > 0) add_settings_field('rpw_reset', sprintf(__('Empty cache (%d %s):', self::language_file), count(self::$options['cache']), $entry), array(&$this, 'reset_field'), 'rpw_styles', 'rpw_settings', array(__('You can empty the plugin&#39;s cache here, if necessary.', self::language_file)));
		
		add_settings_field('rpw_resize', false, array(&$this, 'resize_field'), 'rpw_styles', 'rpw_settings');
	
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
	
	function rpw_display_css($labels) {
		
		echo $labels[0].'</br>'.$labels[1].'</br>';
		
		a5_textarea('css', 'rpw_options[css]', @self::$options['css'], false, array('rows' => 7, 'cols' => 35));
		
	}
	
	function compress_field($labels) {
		
		a5_checkbox('compress', 'rpw_options[compress]', @self::$options['compress'], $labels[0]);
		
	}
	
	function inline_field($labels) {
		
		a5_checkbox('inline', 'rpw_options[inline]', @self::$options['inline'], $labels[0]);
		
	}
	
	function priority_field($labels) {
		
		echo $labels[0].'<br />';
		
		a5_number_field('priority', 'rpw_options[priority]', @self::$options['priority'], false, array('step' => 10));
		
	}
	
	function reset_field($labels) {
		
		a5_checkbox('reset_options', 'rpw_options[reset_options]', @self::$options['reset_options'], $labels[0]);
		
	}
	
	function resize_field() {
		
		a5_resize_textarea(array('link', 'hover', 'rpw_css'), true);
		
	}
		
	function validate($input) {
		
		self::$options['link']=trim($input['link']);
		self::$options['hover']=trim($input['hover']);
		self::$options['css']=trim($input['css']);
		self::$options['compress'] = isset($input['compress']) ? true : false;
		self::$options['inline'] = isset($input['inline']) ? true : false;
		self::$options['priority'] = isset($input['priority']) ? trim($input['priority']) : false;
		
		if (isset($input['reset_options'])) :
		
			self::$options['cache'] = array();
			
			add_settings_error('rpw_options', 'empty-cache', __('Cache emptied.', self::language_file), 'updated');
			
		endif;
		
		return self::$options;
	
	}

} // end of class

?>