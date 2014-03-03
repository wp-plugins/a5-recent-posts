<?php

/**
 *
 * Class RPW Widget
 *
 * @ A5 Recent Post Widget
 *
 * building the actual widget
 *
 */ 
class A5_Recent_Post_Widget extends WP_Widget {
	
	const language_file = 'a5-recent-posts';
 
	function __construct() {
 
		$widget_opts = array( 'description' => __('You can display the most recent post in this widget. Define, on what pages the widget will show.', self::language_file) );
		$control_opts = array( 'width' => 400 );
		
		parent::WP_Widget(false, $name = 'A5 Recents Post', $widget_opts, $control_opts);
	
	}
	
	function form($instance) {
	
		// setup some default settings
		
		$defaults = array(
			'title' => NULL,
			'thumb' => false,
			'image' => false,
			'width' => NULL,
			'link' => NULL,
			'target' => false,
			'headline' => NULL,
			'noshorts' => false,
			'readmore' => false,
			'filter' => false,
			'rmtext' => NULL,
			'rmclass' => NULL,
			'style' => NULL,
			'homepage' => 1,
			'frontpage' => 1,
			'page' => 1,
			'category' => false,
			'single' => 1,
			'date' => false,
			'tag' => false,
			'attachment' => false,
			'taxonomy' => false,
			'author' => false,
			'search' => false,
			'not_found' => false,
			'show_date' => NULL,
			'h' => 3,
			'alignment' => NULL,
			'imgborder' => NULL
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = esc_attr($instance['title']);
		$thumb = esc_attr($instance['thumb']);
		$image = esc_attr($instance['image']);
		$width = esc_attr($instance['width']);
		$link = esc_attr($instance['link']);
		$target = esc_attr($instance['target']);
		$headline = esc_attr($instance['headline']);
		$noshorts = esc_attr($instance['noshorts']);
		$filter = esc_attr($instance['filter']);
		$readmore = esc_attr($instance['readmore']);
		$rmtext = esc_attr($instance['rmtext']);
		$rmclass = esc_attr($instance['rmclass']);
		$style = esc_attr($instance['style']);
		$homepage=esc_attr($instance['homepage']);
		$frontpage=esc_attr($instance['frontpage']);
		$page=esc_attr($instance['page']);
		$category=esc_attr($instance['category']);
		$single=esc_attr($instance['single']);
		$date=esc_attr($instance['date']);
		$tag=esc_attr($instance['tag']);
		$attachment=esc_attr($instance['attachment']);
		$taxonomy=esc_attr($instance['taxonomy']);
		$author=esc_attr($instance['author']);
		$search=esc_attr($instance['search']);
		$not_found=esc_attr($instance['not_found']);
		$show_date=esc_attr($instance['show_date']);
		$h=esc_attr($instance['h']);
		$alignment=esc_attr($instance['alignment']);
		$imgborder = esc_attr($instance['imgborder']);
		
		$link_options = array (array('post', __('The post', self::language_file)), array('extern', __('External link', self::language_file)), array('page', __('The attachment page', self::language_file)), array('file', __('The attachment file', self::language_file)), array('none', __('Don&#39;t link', self::language_file)));
		
		$options = array (array('top', __('Above thumbnail', self::language_file)) , array('bottom', __('Under thumbnail', self::language_file)), array('middel', __('Under date', self::language_file)), array('none', __('Don&#39;t show title', self::language_file)));
		
		$items = array (array('none', __('Under image', self::language_file)), array('right', __('Left of image', self::language_file)), array('left', __('Right of image', self::language_file)), array('notext', __('Don&#39;t show excerpt', self::language_file)));
		
		$date_options = array (array('top', __('Above post', self::language_file)), array('middel', __('Under thumbnail', self::language_file)), array('bottom', __('Under post', self::language_file)), array('none', __('Don&#39;t show date', self::language_file)));
		
		$headings = array(array('1', 'h1'), array('2', 'h2'), array('3', 'h3'), array('4', 'h4'), array('5', 'h5'), array('6', 'h6'));
		
		$base_id = 'widget-'.$this->id_base.'-'.$this->number.'-';
		$base_name = 'widget-'.$this->id_base.'['.$this->number.']';
		
		$pages = array (
				array($base_id.'homepage', $base_name.'[homepage]', $homepage, __('Homepage', self::language_file)),
				array($base_id.'frontpage', $base_name.'[frontpage]', $frontpage, __('Frontpage (e.g. a static page as homepage)', self::language_file)),
				array($base_id.'page', $base_name.'[page]', $page, __('&#34;Page&#34; pages', self::language_file)),
				array($base_id.'category', $base_name.'[category]', $category, __('Category pages', self::language_file)),
				array($base_id.'single', $base_name.'[single]', $single, __('Single post pages', self::language_file)),
				array($base_id.'date', $base_name.'[date]', $date, __('Archive pages', self::language_file)),
				array($base_id.'tag', $base_name.'[tag]', $tag, __('Tag pages', self::language_file)),
				array($base_id.'attachment', $base_name.'[attachment]', $attachment, __('Attachments', self::language_file)),
				array($base_id.'taxonomy', $base_name.'[taxonomy]', $taxonomy, __('Custom Taxonomy pages (only available, if having a plugin)', self::language_file)),
				array($base_id.'author', $base_name.'[author]', $author, __('Author pages', self::language_file)),
				array($base_id.'search', $base_name.'[search]', $search, __('Search Results', self::language_file)),
				array($base_id.'not_found', $base_name.'[not_found]', $not_found, __('&#34;Not Found&#34;', self::language_file))
		);
		
		$checkall = array($base_id.'checkall', $base_name.'[checkall]', __('Check all', self::language_file));
		
		a5_text_field($base_id.'title', $base_name.'[title]', $title, __('Title:', self::language_file), array('space' => true, 'class' => 'widefat'));
		a5_checkbox($base_id.'image', $base_name.'[image]', $image, __('Check to get the first image of the post as thumbnail.', self::language_file), array('space' => true));
		a5_number_field($base_id.'width', $base_name.'[width]', $width, __('Width of the thumbnail (in px):', self::language_file), array('space' => true, 'size' => 4, 'step' => 1));
		a5_select($base_id.'link', $base_name.'[link]', $link_options, $link, __('Choose here to what you want the widget to link to. It will link to the post by default.', self::language_file), false, array('space' => true));
		a5_checkbox($base_id.'target', $base_name.'[target]', $target, __('Check to open the link in another browser window.', self::language_file), array('space' => true));
		a5_checkbox($base_id.'thumb', $base_name.'[thumb]', $thumb, sprintf(__('Check to %snot%s display the thumbnail of the post.', self::language_file), '<strong>', '</strong>'), array('space' => true));
		a5_text_field($base_id.'imgborder', $base_name.'[imgborder]', $imgborder, sprintf(__('If wanting a border around the image, write the style here. %s would make it a black border, 1px wide.', self::language_file), '<strong>1px solid #000000</strong>'), array('space' => true, 'class' => 'widefat'));
		a5_select($base_id.'headline', $base_name.'[headline]', $options, $headline, __('Choose, whether or not to display the title and whether it comes above or under the thumbnail.', self::language_file), false, array('space' => true));
		a5_select($base_id.'h', $base_name.'[h]', $headings, $h, __('Weight of the Post Title:', self::language_file), false, array('space' => true));
		a5_select($base_id.'show_date', $base_name.'[show_date]', $date_options, $show_date, __('Choose, whether or not to display the publishing date and whether it comes above or under the post.', self::language_file), false, array('space' => true));
		a5_select($base_id.'alignment', $base_name.'[alignment]', $items, $alignment, __('Choose, whether or not to display the excerpt and whether it comes under the thumbnail or next to it.', self::language_file), false, array('space' => true));
		a5_checkbox($base_id.'noshorts', $base_name.'[noshorts]', $noshorts, __('Check to suppress shortcodes in the widget (in case the content is showing).', self::language_file), array('space' => true));
		a5_checkbox($base_id.'filter', $base_name.'[filter]', $filter, __('Check to return the excerpt unfiltered (might avoid interferences with other plugins).', self::language_file), array('space' => true));
		a5_checkbox($base_id.'readmore', $base_name.'[readmore]', $readmore, __('Check to have an additional &#39;read more&#39; link at the end of the excerpt.', self::language_file), array('space' => true));	
		a5_text_field($base_id.'rmtext', $base_name.'[rmtext]', $rmtext, sprintf(__('Write here some text for the &#39;read more&#39; link. By default, it is %s:', self::language_file), '[&#8230;]'), array('space' => true, 'class' => 'widefat'));
		a5_text_field($base_id.'rmclass', $base_name.'[rmclass]', $rmclass, __('If you want to style the &#39;read more&#39; link, you can enter a class here.', self::language_file), array('space' => true, 'class' => 'widefat'));
		a5_checkgroup(false, false, $pages, __('Check, where you want to show the widget. By default, it is showing on the homepage and the category pages:', self::language_file), $checkall);
		a5_textarea($base_id.'style', $base_name.'[style]', $style, sprintf(__('Here you can finally style the widget. Simply type something like%sto get just a gray outline and a padding of 10 px. If you leave that section empty, your theme will style the widget.', self::language_file), '<br /><strong>border: 2px solid;<br />border-color: #cccccc;<br />padding: 10px;</strong><br />'), array('space' => true, 'class' => 'widefat', 'style' => 'height: 60px;'));
		a5_resize_textarea(array($base_id.'style'), true);
	
	} // form
	
	function update($new_instance, $old_instance) {
	
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']); 
		$instance['thumb'] = strip_tags($new_instance['thumb']);	 
		$instance['image'] = strip_tags($new_instance['image']);	 
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['link'] = strip_tags($new_instance['link']);
		$instance['target'] = strip_tags($new_instance['target']);
		$instance['headline'] = strip_tags($new_instance['headline']);
		$instance['noshorts'] = strip_tags($new_instance['noshorts']);
		$instance['filter'] = strip_tags($new_instance['filter']);
		$instance['readmore'] = strip_tags($new_instance['readmore']);
		$instance['rmtext'] = strip_tags($new_instance['rmtext']);
		$instance['rmclass'] = strip_tags($new_instance['rmclass']);
		$instance['style'] = strip_tags($new_instance['style']);
		$instance['homepage'] = strip_tags($new_instance['homepage']);
		$instance['frontpage'] = strip_tags($new_instance['frontpage']);
		$instance['page'] = strip_tags($new_instance['page']);
		$instance['category'] = strip_tags($new_instance['category']);
		$instance['single'] = strip_tags($new_instance['single']);
		$instance['date'] = strip_tags($new_instance['date']); 
		$instance['tag'] = strip_tags($new_instance['tag']);
		$instance['attachment'] = strip_tags($new_instance['attachment']);
		$instance['taxonomy'] = strip_tags($new_instance['taxonomy']);
		$instance['author'] = strip_tags($new_instance['author']);
		$instance['search'] = strip_tags($new_instance['search']);
		$instance['not_found'] = strip_tags($new_instance['not_found']);
		$instance['show_date'] = strip_tags($new_instance['show_date']);
		$instance['h'] = strip_tags($new_instance['h']);
		$instance['alignment'] = strip_tags($new_instance['alignment']);
		$instance['imgborder'] = strip_tags($new_instance['imgborder']);
		
		return $instance;
	
	} // update
 
	function widget($args, $instance) {
	
		// get the type of page, we're actually on
		
		if (is_front_page()) $rpw_pagetype='frontpage';
		if (is_home()) $rpw_pagetype='homepage';
		if (is_page()) $rpw_pagetype='page';
		if (is_category()) $rpw_pagetype='category';
		if (is_single()) $rpw_pagetype='single';
		if (is_date()) $rpw_pagetype='date';
		if (is_tag()) $rpw_pagetype='tag';
		if (is_attachment()) $rpw_pagetype='attachment';
		if (is_tax()) $rpw_pagetype='taxonomy';
		if (is_author()) $rpw_pagetype='author';
		if (is_search()) $rpw_pagetype='search';
		if (is_404()) $rpw_pagetype='not_found';
		
		// display only, if said so in the settings of the widget
		
		if ($instance[$rpw_pagetype]) :
		
			// the widget is displayed	
			extract( $args );
			
			$title = apply_filters('widget_title', $instance['title']);
			
			if (empty($instance['style'])) :
			
				$rpw_before_widget=$before_widget;
				$rpw_after_widget=$after_widget;
			
			else :
			
				$style=str_replace(array("\r\n", "\n", "\r"), ' ', $instance['style']);
				
				$rpw_before_widget='<div id="'.$widget_id.'" style="'.$style.'" class="widget_a5_recent_post_widget">';
				$rpw_after_widget='</div>';
			
			endif;
			
			$eol = "\r\n";
			
			// widget starts
			
			echo $rpw_before_widget.$eol;
			
			if ( $title ) echo $before_title . $title . $after_title . $eol;
			
			$rpw_target = (empty ($instance['target'])) ? '' : ' target="_blank"';
			
			/* This is the actual function of the plugin, it fills the widget with the customized post */
			
			global $wp_query, $post;
			
			$rpw_args['posts_per_page'] = 1;
			
			if (is_single()) $rpw_args['post__not_in'] = array($wp_query->get_queried_object_id());
			
			$rpw_posts = new WP_Query($rpw_args);
			
			while($rpw_posts->have_posts()) :
			
				$rpw_posts->the_post();
				
				switch ($instance['link']) :
				
					case 'none' :
					
						$rpw_link = false;
						
						break;
				
					case 'post' :
					
						$rpw_link = get_permalink();
						
						break;
				
					case 'extern' :
					
						$rpw_link = preg_match_all('/<a.+href=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
						$rpw_link = $matches [1] [0];
						
						break;
					
					default :
					
						$args = array(
							'post_type' => 'attachment',
							'numberposts' => 1,
							'post_status' => null,
							'post_parent' => $post->ID
						);
						
						$rpw_attachments = get_posts( $args );
						
						if ( $rpw_attachments ) :
						
							foreach ( $rpw_attachments as $attachment ) :
							
								if ($instance['link'] == 'page') $rpw_link = get_attachment_link( $attachment->ID );
								
								if ($instance['link'] == 'file') $rpw_link = wp_get_attachment_url( $attachment->ID );
							
							endforeach;
							
						else :
						
							$rpw_link = false;
						
						endif;
						
						break;
					
					endswitch;
					
					$rpw_tags = A5_Image::tags($post, 'rpw_options', self::language_file);
					
					$rpw_image_alt = $rpw_tags['image_alt'];
					$rpw_image_title = $rpw_tags['image_title'];
					$rpw_title_tag = $rpw_tags['title_tag'];
					
					$rpw_style = ($instance['alignment'] != 'notext' && $instance['alignment'] != 'none') ? ' style="text-align: '.$instance['alignment'].';"' : '';
					
					// headline, if wanted
					
					if ($instance['headline'] != 'none') :
					
						$rpw_link_tag = ($rpw_link) ? $eol.'<a href="'.$rpw_link.'" title="'.$rpw_title_tag.'"'.$rpw_target.'>'.get_the_title().'</a>'.$eol : $eol.get_the_title().$eol;
					
						$rpw_headline = '<h'.$instance['h'].$rpw_style.'>'.$rpw_link_tag.'</h'.$instance['h'].'>';
						
					endif;
					
					// date, if wanted
					
					if ($instance['show_date'] != 'none') $post_date = $eol.'<p'.$rpw_style.'>'.get_the_date().'</p>';
				
					// thumbnail, if wanted
				
					if (!$instance['thumb']) :
					
						$rpw_imgborder = (isset($instance['imgborder'])) ? ' border: '.$instance['imgborder'].';' : '';
					
						$default = A5_Image::get_default($instance['width']);
						
						$rpw_float = ($instance['alignment'] != 'notext') ? $instance['alignment'] : 'none';
				
						$rpw_margin = '';
						if ($instance['alignment'] == 'left') $rpw_margin = ' margin-right: 1em;';
						if ($instance['alignment'] == 'right') $rpw_margin = ' margin-left: 1em;';
						
						if (!has_post_thumbnail() || $instance['image']) : 
						
							$args = array (
								'content' => $post->post_content,
								'width' => $default[0],
								'height' => $default[1], 
								'option' => 'rpw_options'
							);	
						   
							$rpw_image_info = A5_Image::thumbnail($args);
							
							$rpw_thumb = $rpw_image_info['thumb'];
							
							$rpw_width = $rpw_image_info['thumb_width'];
					
							$rpw_height = $rpw_image_info['thumb_height'];
							
							if ($rpw_thumb) :
							
								if ($rpw_width) $rpw_img_tag = '<img title="'.$rpw_image_title.'" src="'.$rpw_thumb.'" alt="'.$rpw_image_alt.'" class="wp-post-image" width="'.$rpw_width.'" height="'.$rpw_height.'" style="float: '.$rpw_float.';'.$rpw_margin.$rpw_imgborder.'" />';
									
								else $rpw_img_tag = '<img title="'.$rpw_image_title.'" src="'.$rpw_thumb.'" alt="'.$rpw_image_alt.'" class="wp-post-image" style="maxwidth: '.$width.'; maxheight: '.$height.'; float: '.$rpw_float.';'.$rpw_margin.$rpw_imgborder.'" />';
								
							endif;
							
						else :
						
							$img_info = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
							
							if (!$img_info):
							
								$src = get_the_post_thumbnail();
								
								$img = preg_match_all('/<\s*img[^>]+src\s*=\s*["\']?([^\s"\']+)["\']?[\s\/>]+/', $src, $matches);
								
								if ($img): 
								
									$img_info[0] = $matches[1][0];
									
									$img_size = A5_Image::get_size($img_info[0]);
									
									$img_info[1] = $img_size['width'];
									
									$img_info[2] = $img_size['height'];
									
								endif;
								
							endif;
						
						if ($img_info) :
						
							$args = array (
								'ratio' => $img_info[1]/$img_info[2],
								'thumb_width' => $img_info[1],
								'thumb_height' => $img_info[2],
								'width' => $default[0],
								'height' => $default[1]
							);
							
							$img_size = A5_Image::count_size($args);
							
							$atts = array('title' => $rpw_image_title, 'alt' => $rpw_image_alt, 'style' => $rpw_imgborder);
							
							$size = array($img_size['width'], $img_size['height']);
						
							$rpw_img_tag = get_the_post_thumbnail($post->ID, $size, $atts);
							
						endif;
						
					endif;
					
					if (!empty($rpw_img_tag)) :
					
						$rpw_image = ($rpw_link) ? '<a href="'.$rpw_link.'">'.$rpw_img_tag.'</a>'.$eol : $rpw_img_tag.$eol;
						
						$rpw_image .= '<div style="clear: both;"></div>'.$eol;
						
					endif;
					
				endif;
				
				// excerpt if wanted
				
				if ($instance['alignment'] != 'notext') :
				
					$rmtext = ($instance['rmtext']) ? $instance['rmtext'] : '[&#8230;]';
					
					$shortcode = ($instance['noshorts']) ? false : 1;
					
					$filter = ($instance['filter']) ? false : true;
				
					$args = array(
						'excerpt' => $post->post_excerpt,
						'content' => $post->post_content,
						'shortcode' => $shortcode,
						'link' => get_permalink(),
						'title' => $rpw_title_tag,
						'readmore' => $instance['readmore'],
						'rmtext' => $rmtext,
						'class' => $instance['rmclass'],
						'filter' => $filter
					);
					
					$rpw_text = A5_Excerpt::text($args);
				
				endif;
				
				// writing the stuff in the widget
				
				if ($instance['headline'] == 'top') echo $rpw_headline.$eol;
				
				if ($instance['show_date'] == 'top') echo $post_date.$eol;
				
				if ($instance['show_date'] == 'top' && $instance['headline'] == 'middel') echo $rpw_headline.$eol;
				
				if (!$instance['thumb']) echo $rpw_image;
				
				if ($instance['headline'] == 'bottom') echo $rpw_headline.$eol;
				
				if ($instance['show_date'] == 'middel') echo $post_date.$eol;
				
				if ($instance['show_date'] == 'middel' && $instance['headline'] == 'middel') echo $rpw_headline.$eol;
				
				if ($instance['alignment'] == 'left' || $instance['alignment'] == 'right') echo $eol.do_shortcode($rpw_text).$eol;
				
				echo '<div style="clear: both;"></div>'.$eol;
				
				if ($instance['alignment'] == 'none') echo do_shortcode($rpw_text).$eol;
				
				if ($instance['show_date'] == 'bottom') echo $post_date.$eol;
				
				if ($instance['show_date'] == 'bottom' && $instance['headline'] == 'middel') echo $rpw_headline.$eol;
			
			endwhile;
			
			// Restore original Query & Post Data
			wp_reset_query();
			wp_reset_postdata();
			
			echo $rpw_after_widget;
		
		else:
		
			echo '<!-- A5 Recent Post Widget is not setup for this view. -->';
		
		endif;
		
	} // widget
 
} // end of class
add_action('widgets_init', create_function('', 'return register_widget("A5_Recent_Post_Widget");'));

?>