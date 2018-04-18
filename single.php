<?php get_header(); 

//  get post meta
$wSource = get_post_meta( $post->ID, 'source', 1 );
$wAuthor = get_post_meta( $post->ID, 'shared_by', 1 );
$wLicense = get_post_meta( $post->ID, 'license', 1 );
$wExtraNotes = get_post_meta( $post->ID, 'extra_notes', 1 );
?>

<div class="content thin">
											        
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('single'); ?>>
		
			<?php $post_format = get_post_format(); ?>
			
			<?php if ( $post_format == 'video' ) : ?>
			
				<?php if ($pos=strpos($post->post_content, '<!--more-->')): ?>
		
					<div class="featured-media">
					
						<?php
								
							// Fetch post content
							$content = get_post_field( 'post_content', get_the_ID() );
							
							// Get content parts
							$content_parts = get_extended( $content );
							
							// oEmbed part before <!--more--> tag
							$embed_code = wp_oembed_get($content_parts['main']); 
							
							echo $embed_code;
						
						?>
					
					</div> <!-- /featured-media -->
				
				<?php endif; ?>
				
			<?php elseif ( $post_format == 'gallery' ) : ?>
			
				<div class="featured-media">	
	
					<?php fukasawa_flexslider('post-image'); ?>
					
					<div class="clear"></div>
					
				</div> <!-- /featured-media -->
							
			<?php elseif ( has_post_thumbnail() ) : ?>
					
				<div class="featured-media">
		
					<?php the_post_thumbnail('post-image'); ?>
					
				</div> <!-- /featured-media -->
					
			<?php endif; ?>
			
			<div class="post-inner">
				
				<div class="post-header">
													
					<h1 class="post-title"><?php the_title(); ?></h1>
					
					<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
															
				</div> <!-- /post-header -->
				    
			    <div class="post-content">
			    
			    	<?php if ( $wAuthor == '')  : // empty meta data for auhor means post by email ?>
			    	
			    	<?php
			    	// strip the img tags out of content for stuff sent my email
						$content = get_the_content();
						$content = preg_replace("/<img[^>]+\>/i", "", $content); 		  
						$content = apply_filters('the_content', $content);
						$content = str_replace(']]>', ']]>', $content);
						echo $content;
			    	?>
			    	
			    	<hr />
			    	
			    	<p><em>This item was posted by email.</em></p>
			    	
			    	
			    	<?php else:?>
			    
			    	<?php 
						if ($post_format == 'video') { 
							$content = $content_parts['extended'];
							$content = apply_filters('the_content', $content);
							echo $content;
						} else {
							the_content();
						}
					?>
					
			    	<p>
			    	<?php 
			    		// show extra notes
			    		if ( $wExtraNotes ) echo '<strong>Extra Notes:</strong> ' .  $wExtraNotes . '<br />';
			    		
			    		// Sharer
			    		echo '<strong>Shared by:</strong> ' . $wAuthor . '<br />';
			    		
			    		
			    		if ( ( trucollector_option('use_source') > 0 )  AND $wSource ) echo '<strong>Image Credit:</strong> ' .  make_links_clickable($wSource)  . '<br />';
			    		
			    		
			    		if  ( trucollector_option('use_license') > 0 ) {
			    			echo '<strong>Reuse License:</strong> ';
			    			trucollector_the_license( $wLicense );
			    			echo '<br />';
			    			
			    			// display attribution?
			    			if  ( trucollector_option( 'show_attribution' ) == 1 ) {
			    				echo '<strong>Attribution Text:</strong><br /><textarea rows="2" onClick="this.select()" style="height:80px;">' . trucollector_attributor( $wLicense, get_the_title(), $wSource ) . '</textarea>';
			    			}
			    		}
			    		
			    		
			    		
			    	?>
			    	</p>
			    	<?php endif;?>
			    	
			    	<form>
			    	<label for="link">Link to image:</label>
						<input type="text" class="form-control" id="link" value="<?php $iurl = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); echo $iurl[0];  ?>" onClick="this.select();" />
			    	</form>					
					
			    
			    </div> <!-- /post-content -->
			    
			    <div class="clear"></div>
				
				<div class="post-meta-bottom">
				
					<?php 
				    	$args = array(
							'before'           => '<div class="clear"></div><p class="page-links"><span class="title">' . __( 'Pages:','fukasawa' ) . '</span>',
							'after'            => '</p>',
							'link_before'      => '<span>',
							'link_after'       => '</span>',
							'separator'        => '',
							'pagelink'         => '%',
							'echo'             => 1
						);
			    	
			    		wp_link_pages($args); 
					?>
				
					<ul>
						<li class="post-date"><a href="<?php the_permalink(); ?>"><?php the_date(get_option('date_format')); ?></a></li>
						<?php if (has_category()) : ?>
							<li class="post-categories"><?php _e('In','fukasawa'); ?> <?php the_category(', '); ?></li>
						<?php endif; ?>
						<?php if (has_tag()) : ?>
							<li class="post-tags"><?php the_tags('', ' '); ?></li>
						<?php endif; ?>
						<?php edit_post_link('Edit post', '<li>', '</li>'); ?>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- /post-meta-bottom -->
			
			</div> <!-- /post-inner -->
			
			<?php
				$prev_post = get_previous_post();
				$next_post = get_next_post();
			?>
			
			<div class="post-navigation">
			
				<?php
				if (!empty( $prev_post )): ?>
				
					<a class="post-nav-prev" title="<?php _e('Previous post', 'fukasawa'); echo ': ' . esc_attr( get_the_title($prev_post) ); ?>" href="<?php echo get_permalink( $prev_post->ID ); ?>">
						<p>&larr; <?php _e('Previous item', 'fukasawa'); ?></p>
					</a>
				<?php endif; ?>
				
				<?php
				if (!empty( $next_post )): ?>
					
					<a class="post-nav-next" title="<?php _e('Next post', 'fukasawa'); echo ': ' . esc_attr( get_the_title($next_post) ); ?>" href="<?php echo get_permalink( $next_post->ID ); ?>">					
						<p><?php _e('Next item', 'fukasawa'); ?> &rarr;</p>
					</a>
			
				<?php endif; ?>
				
				<div class="clear"></div>
			
			</div> <!-- /post-navigation -->
								
			<?php if ( trucollector_option('allow_comments') ) comments_template( '', true ); ?>
		
		</div> <!-- /post -->
									                        
   	<?php endwhile; else: ?>

		<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "fukasawa"); ?></p>
	
	<?php endif; ?>    

</div> <!-- /content -->
		
<?php get_footer(); ?>