<?php 

class fukasawa_recent_posts extends WP_Widget {

	function fukasawa_recent_posts() {
		parent::WP_Widget(false, $name = 'Recent posts', array('description' => __('Displays recent blog entries.', 'fukasawa') ));	
	}
	
	function widget($args, $instance) {
	
		// Outputs the content of the widget
		extract($args); // Make before_widget, etc available.
		
		$widget_title = apply_filters('widget_title', $instance['widget_title']);
		$number_of_posts = $instance['number_of_posts'];
		
		echo $before_widget;
		
		
		if (!empty($widget_title)) {
		
			echo $before_title . $widget_title . $after_title;
			
		} ?>
		
			<ul>
				
				<?php
					global $wp_query;
					global $post;
					
					$sticky = get_option( 'sticky_posts' );					
					
					$not_in[] = $sticky[0];
					
					if ( $number_of_posts == 0 ) $number_of_posts = 5;
					
					query_posts(
						array(
							'post_type' => 'post',
							'post__not_in' => $not_in, 
							'posts_per_page' => $number_of_posts,
							'post_status' => 'publish'
						)
					);
					
					while ( have_posts() ) : the_post();
				?>
				
				<li>
				
					<?php global $post; ?>
				
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							
						<div class="post-icon">
						
							<?php 
								$post_format = get_post_format(); 
								if ( empty($post_format) ) { $post_format = 'standard'; }
							?>
							
							<?php if ( has_post_thumbnail() ) : ?>
							
								<?php the_post_thumbnail('thumbnail') ?>
							
							<?php elseif ( $post_format == 'gallery' ) : ?>
						
								<?php
									$attachment_parent = get_the_ID();
				
									if($images = get_posts(array(
										'post_parent'    => $attachment_parent,
										'post_type'      => 'attachment',
										'numberposts'    => 1,
										'post_status'    => null,
										'post_mime_type' => 'image',
								                'orderby'        => 'menu_order',
								                'order'           => 'ASC',
									))) { ?>
					
									<?php foreach($images as $image) { 
										$attimg = wp_get_attachment_image($image->ID, 'thumbnail'); ?>
										
										<?php echo $attimg; ?>
										
									<?php } ?>
									
								<?php } ?>
								
							<?php else : ?>
							
								<div class="genericon genericon-<?php echo $post_format; ?>"></div>
							
							<?php endif; ?>
							
						</div>
						
						<div class="inner">
										
							<p class="title"><?php the_title(); ?></p>
							<p class="meta"><?php the_time(get_option('date_format')); ?></p>
						
						</div>
						
						<div class="clear"></div>
											
					</a>
					
				</li>
			
			<?php endwhile; wp_reset_query(); ?>
			
			</ul>
					
		<?php echo $after_widget; 
	}
	
	
	function update($new_instance, $old_instance) {
	
		//update and save the widget
		return $new_instance;
		
	}
	
	function form($instance) {
	
		// Get the options into variables, escaping html characters on the way
		$widget_title = $instance['widget_title'];
		$number_of_posts = $instance['number_of_posts'];
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('widget_title'); ?>"><?php  _e('Title:', 'fukasawa'); ?>:
			<input id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" class="widefat" value="<?php echo $widget_title; ?>" /></label>
		</p>
						
		<p>
			<label for="<?php echo $this->get_field_id('number_of_posts'); ?>"><?php _e('Number of posts to display:', 'fukasawa'); ?>
			<input id="<?php echo $this->get_field_id('number_of_posts'); ?>" name="<?php echo $this->get_field_name('number_of_posts'); ?>" type="text" class="widefat" value="<?php echo $number_of_posts; ?>" /></label>
			<small>(<?php _e('Defaults to 5 if empty','fukasawa'); ?>)</small>
		</p>
		
		<?php
	}
}
register_widget('fukasawa_recent_posts'); ?>