<div class="post-container">

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<div class="featured-media">	
			
			<?php fukasawa_flexslider('post-thumb'); ?>
			
		</div> <!-- /featured-media -->
		
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

</div> <!-- /post-container -->