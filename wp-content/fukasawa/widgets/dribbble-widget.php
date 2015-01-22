<?php 

include_once(ABSPATH . WPINC . '/feed.php');

class fukasawa_dribbble_widget extends WP_Widget {

	function fukasawa_dribbble_widget() {
		parent::WP_Widget(false, $name = 'Dribbble widget', array('description' => __('Displays your latest Dribbble photos.', 'fukasawa') ));	
	}
	
	function widget($args, $instance) {
	
		extract($args);
		
		$widget_title = apply_filters('widget_title', $instance['widget_title']);
		$dribbble_username = $instance['dribbble_username'];
		$dribbble_number = $instance['dribbble_number'];
		$unique_id = $args['widget_id'];
		
		echo $before_widget;
		
		if (!empty($widget_title)) {
		
			echo $before_title . $widget_title . $after_title;
			
		}
		
			$rss = fetch_feed("http://dribbble.com/players/$dribbble_username/shots.rss");
			add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 1800;' ) );
			if (!is_wp_error( $rss ) ) : 
				$items = $rss->get_items(0, $rss->get_item_quantity($dribbble_number)); 
			endif;
		
			if (!empty($items)): ?>
			
				<div class="dribbble-container">
						
					<?php foreach ( $items as $item ):
						$title = $item->get_title();
						$link = $item->get_permalink();
						$description = $item->get_description();
						
						preg_match("/src=\"(http.*(jpg|jpeg|gif|png))/", $description, $image_url);
						$image = $image_url[1]; ?>
																												
							<a href="<?php echo $link; ?>" title="<?php echo $title;?>" class="dribbble-shot"><img src="<?php echo $image; ?>" alt="<?php echo $title;?>"/></a> 
																																			 	
					<?php endforeach; ?>
									
				</div>
							
			<?php endif;
	
			echo $after_widget;
		
		}
	
	
	function update($new_instance, $old_instance) {
	
		//update and save the widget
		return $new_instance;
		
	}
	
	function form($instance) {
	
		// Get the options into variables, escaping html characters on the way
		$widget_title = $instance['widget_title'];
		$dribbble_username = $instance['dribbble_username'];
		$dribbble_number = $instance['dribbble_number'];
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('widget_title'); ?>"><?php  _e('Title:', 'fukasawa'); ?>:
			<input id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" class="widefat" value="<?php echo $widget_title; ?>" /></label>
		</p>
				
		
		<p>
			<label for="<?php echo $this->get_field_id('dribbble_username'); ?>"><?php  _e('Dribbble username:', 'fukasawa'); ?>:
			<input id="<?php echo $this->get_field_id('dribbble_username'); ?>" name="<?php echo $this->get_field_name('dribbble_username'); ?>" type="text" class="widefat" value="<?php echo $dribbble_username; ?>" /></label>
		</p>
		
		
		<p>
			<label for="<?php echo $this->get_field_id('dribbble_number'); ?>"><?php _e('Number of images to display:', 'fukasawa'); ?>
			<input id="<?php echo $this->get_field_id('dribbble_number'); ?>" name="<?php echo $this->get_field_name('dribbble_number'); ?>" type="text" class="widefat" value="<?php echo $dribbble_number; ?>" /></label>
		</p>
		
		<?php
	}
}
register_widget('fukasawa_dribbble_widget'); ?>