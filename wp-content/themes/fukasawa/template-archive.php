<?php
/*
Template Name: Archive Template
*/
?>

<?php get_header(); ?>

<div class="content thin">						

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div <?php post_class('post single'); ?>>
		
			<div class="post-inner">
	
				<div class="post-header">
																										
					<h2 class="post-title"><?php the_title(); ?></h2>
														
				</div> <!-- /post-header -->
			   				        			        		                
				<div class="post-content">
											                                        
					<?php the_content(); ?>
										
				</div> <!-- /post-content -->
				
				<div class="clear"></div>
				
				<div class="archive-container">
				
					<h3><?php _e('Posts','fukasawa') ?></h3>
										            
		            <ul class="posts-archive-list">
			            <?php $posts_archive = get_posts('numberposts=-1');
			            foreach($posts_archive as $post) : ?>
			                <li>
			                	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			                		<?php the_title();?> 
			                	</a>
			                	<span><?php the_time(get_option('date_format')); ?></span>
			                </li> 
			            <?php endforeach; ?>
		            </ul>
		            
		            <?php wp_reset_query(); ?>
		            
		            <h3><?php _e('Categories','fukasawa') ?></h3>
		            
		            <ul>	            
			            <?php wp_list_categories('title_li='); ?>
		            </ul>
		            
		            <h3><?php _e('Tags','fukasawa') ?></h3>
		            
		            <ul>
		                <?php $tags = get_tags();
		                
		                if ($tags) {
		                    foreach ($tags as $tag) {
		                 	   echo '<li><a href="' . get_tag_link( $tag->term_id ) . '" title="' . sprintf( __( "View all posts in %s", 'fukasawa' ), $tag->name ) . '" ' . '>' . $tag->name.'</a></li> ';
		                    }
		                }
		                
		                wp_reset_query();?>
		            </ul>
		            
		            <h3><?php _e('Contributors', 'fukasawa') ?></h3>
	            	
	            	<ul>
	            		<?php wp_list_authors(); ?> 
	            	</ul>
	            	
	            	<h3><?php _e('Archives by Year', 'fukasawa') ?></h3>
	            	
	            	<ul>
	            	    <?php wp_get_archives('type=yearly'); ?>
	            	</ul>
	            	
	            	<h3><?php _e('Archives by Month', 'fukasawa') ?></h3>
	            	
	            	<ul>
	            	    <?php wp_get_archives('type=monthly'); ?>
	            	</ul>
	            
		            <h3><?php _e('Archives by Day', 'fukasawa') ?></h3>
		            
		            <ul>
		                <?php wp_get_archives('type=daily'); ?>
		            </ul>
	        
	            </div> <!-- /archive-container -->
            
			</div> <!-- /post-inner -->
			
			<?php if ( comments_open() ) : ?>
			
				<?php comments_template( '', true ); ?>
							
			<?php endif; ?>

		</div> <!-- /post -->
			
	<?php endwhile; else: ?>

		<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "fukasawa"); ?></p>

	<?php endif; ?>
	
</div> <!-- /content -->
								
<?php get_footer(); ?>