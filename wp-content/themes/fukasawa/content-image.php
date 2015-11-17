<div class="post-container">

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<?php if ( has_post_thumbnail() ) : ?>
		
			<div class="featured-media">	
				
				<?php the_post_thumbnail('post-thumb'); ?>
				
				<a class="post-overlay" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
				
					<p class="view"><?php _e('View','fukasawa'); ?> &rarr;</p>
				
				</a>
				
			</div> <!-- /featured-media -->
				
		<?php else : ?>
		
			<div class="post-header">
			
				<?php 
					$post_title = get_the_title();
					if ( !empty( $post_title ) ) : 
				?>
				
				    <h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			    
			    <?php else : ?>
			    
				    <div class="posts-meta">
					
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_time(get_option('date_format')); ?></a>
						
					</div>
					
				<?php endif; ?>
			    	    
			</div> <!-- /post-header -->
		
		<?php endif; ?>
					
	</div> <!-- /post -->

</div> <!-- /post-container -->