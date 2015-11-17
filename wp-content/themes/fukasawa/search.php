<?php get_header(); ?>

	<?php if ( have_posts() ) : ?>

		<div class="content">
		
			<div class="page-title">
			
				<h4><?php _e( 'Search results:', 'fukasawa'); echo ' "' . get_search_query() . '"'; ?>
				
				<?php
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				
				if ( "1" < $wp_query->max_num_pages ) : ?>
				
					<span><?php printf( __('Page %s of %s', 'fukasawa'), $paged, $wp_query->max_num_pages ); ?></span>
					
					<div class="clear"></div>
				
				<?php endif; ?></h4>
								
			</div>
					
			<div class="posts" id="posts">
				
				<?php while (have_posts()) : the_post(); ?>
		    	
		    		<?php get_template_part( 'content', get_post_format() ); ?>
		    			        		            
		        <?php endwhile; ?>
							
			</div> <!-- /posts -->
			
			<?php if ( $wp_query->max_num_pages > 1 ) : ?>
			
				<div class="archive-nav">
				
					<?php echo get_next_posts_link( '&laquo; ' . __('Older posts', 'fukasawa')); ?>
						
					<?php echo get_previous_posts_link( __('Newer posts', 'fukasawa') . ' &raquo;'); ?>
					
					<div class="clear"></div>
					
				</div> <!-- /post-nav archive-nav -->
								
			<?php endif; ?>
			
		</div> <!-- /content -->
	
	<?php else : ?>
	
		<div class="content thin">
						
			<div class="page-title">
		
				<h4>
					<?php _e( 'Search results:', 'fukasawa'); echo ' "' . get_search_query() . '"'; ?>
				</h4>
				
			</div> <!-- /page-title -->
						
			<div class="post single">
			
				<div class="post-inner">
			
					<div class="post-content">
					
						<p><?php _e('No results. Try again, would you kindly?', 'fukasawa'); ?></p>
						
						<?php get_search_form(); ?>
					
					</div> <!-- /post-content -->
				
				</div> <!-- /post-inner -->
				
				<div class="clear"></div>
			
			</div> <!-- /post -->
		
		</div> <!-- /content -->
	
	<?php endif; ?>
		
<?php get_footer(); ?>