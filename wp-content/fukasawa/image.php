<?php get_header(); ?>

<div class="content thin">
											        
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div id="post-<?php the_ID(); ?>" <?php post_class('single post'); ?>>
				
			<div class="featured-media">

				<?php echo wp_get_attachment_image( $post->ID, 'post-image' ); ?>
				
			</div>
			
			<div class="post-inner">
			
				<div class="post-header">
				
					<h2 class="post-title"><?php echo basename(get_attached_file( $post->ID )); ?></h2>
				
				</div> <!-- /post-header -->
				
				<?php if ( !empty(get_post(get_post_thumbnail_id())->post_excerpt) ) : ?>
													
					<div class="post-content section-inner thin">
					
						<p class="p"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>
						
					</div>
					
				<?php endif; ?>
				
				<div class="post-meta-bottom">
				
					<ul>
						<li><?php _e('Uploaded by','fukasawa'); ?> <?php the_author_posts_link(); ?></p>
						<li class="post-date"><a href="<?php the_permalink(); ?>"><?php the_date(get_option('date_format')); ?></a></li>
						
						<?php $imageArray = wp_get_attachment_image_src($post->ID, 'full', false); $url = $imageArray['0']; ?>
						<li><?php _e('Resolution:','fukasawa'); ?> <?php echo $imageArray['1'] . 'x' . $imageArray['2'] . ' px'; ?></li>
					</ul>
					
					<div class="clear"></div>
				
				</div> <!-- /post-meta-bottom -->
				
			</div> <!-- /post-inner -->
			
			<?php comments_template( '', true ); ?>
														                        
	   	<?php endwhile; else: ?>
	
			<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "fukasawa"); ?></p>
		
		<?php endif; ?>    
			
	</div> <!-- /post -->

</div> <!-- /content -->
		
<?php get_footer(); ?>