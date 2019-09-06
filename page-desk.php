<?php

/*
Template Name: Welcome Desk
*/
?>

<?php get_header(); ?>

<div class="content thin">
											        
	<?php 
	
	if ( have_posts() ) : 
		
		while ( have_posts() ) : the_post(); 
		
			?>
			
			<div id="post-<?php the_ID(); ?>" <?php post_class( 'post single' ); ?>>		
			
			<?php if ( has_post_thumbnail() ) : ?>
			
				<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail_size' ); $thumb_url = $thumb['0']; ?>
		
				<?php elseif ( has_post_thumbnail() ) : ?>
						
					<div class="featured-media">
			
						<?php the_post_thumbnail( 'post-image' ); ?>
						
					</div><!-- .featured-media -->
						
				<?php endif; ?>

			
				<div class="post-inner">
					
					<div class="post-header">
														
						<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>
						
																
					</div><!-- .post-header -->
				    
			    <div class="post-content">
			    
			    	<?php the_content(); ?>
			    		
					<?php  
						
						// defaultz
						$wAccess = '';

						// get the passcode needed to enter
						$wAccessCode = trucollector_option('accesscode');


						// already logged in but as different user on multisite?
	
						if ( is_user_logged_in() and !trucollector_check_user()  ) {
							// we need to force a click through a logout
							return '<div class="notify notify-green"><span class="symbol icon-tick"></span>' .'Now <a href="' . splot_redirect_url() . '" class="pretty-button pretty-button-green">activate the writing tool</a>.</div>';	  	
						}

						// verify that a  form was submitted and it passes the nonce check
						if ( isset( $_POST['trucollector_form_access_submitted'] ) 
								&& wp_verify_nonce( $_POST['trucollector_form_access_submitted'], 'trucollector_form_access' ) ) {
 
							// grab the variables from the form
							$wAccess = 	stripslashes( $_POST['wAccess'] );
	
							// let's do some validation of the code
							if ( $wAccess != $wAccessCode ) {
								echo '<div class="notify notify-red"><span class="symbol icon-error"></span> <p><strong>Incorrect Access Code</strong> - try again? Hint: ' . trucollector_option('accesshint') . '</p></div>'; 	

							} // end form submmitted check
						}
						?>   

	 					<form  id="trucollectordesk" class="trucollectordesk" method="post" action="">
					
								<fieldset>
									<label for="wAccess"><?php _e('Access Code', 'fukasawa' ) ?></label><br />
									<p>Enter the secret code</p>
									<input type="text" name="wAccess" id="wAccess" class="required" value="<?php echo $wAccess; ?>" tabindex="1" />
								</fieldset>	
			
								<fieldset>
									<?php wp_nonce_field( 'trucollector_form_access', 'trucollector_form_access_submitted' ); ?>
									<input type="submit" class="pretty-button pretty-button-blue" value="Check Code" id="checkit" name="checkit" tabindex="15">
								</fieldset>
				
						</form>
					
			    	<?php wp_link_pages('before=<div class="clear"></div><p class="page-links">' . __('Pages:','fukasawa') . ' &after=</p>&seperator= <span class="sep">/</span> '); ?>
			    
			    </div>
	
			</div> <!-- /post-inner -->
			
		
			</div><!-- .post -->
																
			<?php 
		endwhile; 

	endif; 
	
	?>

</div><!-- .content -->
		
<?php get_footer(); ?>