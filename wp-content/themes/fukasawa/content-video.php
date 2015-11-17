<div class="post-container">

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<?php $video_url = get_post_meta($post->ID, 'video_url', true); if ( $video_url != '' ) : ?>
		
			<div class="featured-media">
			
				<?php if (strpos($video_url,'.mp4') !== false) : ?>
					
					<video controls>
					  <source src="<?php echo esc_url( $video_url); ?>" type="video/mp4">
					</video>
																			
				<?php else : ?>
					
					<?php 
					
						$embed_code = wp_oembed_get( $video_url ); 
						
						echo $embed_code;
						
					?>
						
				<?php endif; ?>
				
			</div>
		
		<?php endif; ?>
		
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
	
	</div> <!-- /post -->

</div> <!-- /post -->