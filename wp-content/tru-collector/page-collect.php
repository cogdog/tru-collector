<?php

if ( !is_user_logged_in() ) {
	// already not logged in? go to desk.
  	wp_redirect ( site_url() . '/desk' );
  	exit;
  	
} elseif ( !current_user_can( 'edit_others_posts' ) ) {
	// okay user, who are you? we know you are not an admin or editor
		
	// if the collector user not found, we send you to the desk
	if ( !trucollector_check_user() ) {
		// now go to the desk and check in properly
	  	wp_redirect ( site_url() . '/desk' );
  		exit;
  	}
}

		

// ------------------------ defaults ------------------------

// default welcome message
$feedback_msg = 'Add an image to this collection? We have a form for you!';

$wTitle = 'Descriptive Title for the Image';
$wAuthor = 'Anonymous';
				
$wFeatureImageID = 0;
$wCats = array( trucollector_option('def_cat')); // preload default category
$wLicense = 'cc-by';
$all_licenses = trucollector_get_licences();


// not yet saved
$is_published = false;
$box_style = '<div class="notify"><span class="symbol icon-info"></span> ';


// ------------------- form processing ------------------------

// verify that a form was submitted and it passes the nonce check
if ( isset( $_POST['trucollector_form_make_submitted'] ) && wp_verify_nonce( $_POST['trucollector_form_make_submitted'], 'trucollector_form_make' ) ) {
 
 		// grab the variables from the form
 		$wTitle = 					sanitize_text_field( stripslashes( $_POST['wTitle'] ) );
 		$wAuthor = 					( isset ($_POST['wAuthor'] ) ) ? sanitize_text_field( stripslashes($_POST['wAuthor']) ) : 'Anonymous';		
 		$wTags = 					sanitize_text_field( $_POST['wTags'] );	
 		$wText = 					sanitize_text_field( stripslashes( $_POST['wText'] ) );
 		$wSource = 					sanitize_text_field( stripslashes( $_POST['wSource'] ) );
 		$wCredit = 					sanitize_text_field( $_POST['wCredit']  );
 		$wNotes = 					sanitize_text_field( stripslashes( $_POST['wNotes'] ) );
 		$wExtraNotes = 				sanitize_text_field( stripslashes( $_POST['wExtraNotes'] ) );
 		$wFeatureImageID = 			$_POST['wFeatureImage'];
 		$post_id = 					$_POST['post_id'];
 		$wCats = 					( isset ($_POST['wCats'] ) ) ? $_POST['wCats'] : array();
 		
 		

 		
 		// let's do some validation, store an error message for each problem found
 		$errors = array();
 		
 		
 		if ( $wFeatureImageID == 0) $errors[] = '<strong>Image File Missing</strong> - upload the image you wish to add.';
 		if ( $wTitle == '' ) $errors[] = '<strong>Title Missing</strong> - enter an interesting title.'; 
 		
 		if ( $wCredit == '' ) $errors[] = '<strong>Credit Missing</strong> - enter the name of someone to give credit for the image.'; 
 		
 		if ( strlen($wText) < 20 ) $errors[] = '<strong>Caption Too Brief</strong> - that\'s not much text, eh? Please provide at least a good sentence for the caption of the image.';	
 		
 		if ( count($errors) > 0 ) {
 			// form errors, build feedback string to display the errors
 			$feedback_msg = 'Sorry, but there are a few errors in your information. Please correct and try again. We really want to add your entry.<ul>';
 			
 			// Hah, each one is an oops, get it? 
 			foreach ($errors as $oops) {
 				$feedback_msg .= '<li>' . $oops . '</li>';
 			}
 			
 			$feedback_msg .= '</ul>';
 			
 			$box_style = '<div class="notify notify-red"><span class="symbol icon-error"></span> ';
 			
 		} else {
 			
 			// good enough, let's make a post! 
 			 			
			$w_information = array(
				'post_title' => $wTitle,
				'post_content' => $wText,
				'post_status' => trucollector_option('new_item_status'),
				'post_category' => $wCats		
			);

			// insert as a new post
			$post_id = wp_insert_post( $w_information );
			
			// store the author as post meta data
			add_post_meta($post_id, 'shared_by', $wAuthor);

			// store the source of the image (text or URL)
			add_post_meta($post_id, 'source', $wSource);
			
			// store the name of person to credit
			add_post_meta($post_id, 'credit', $wCredit);

			// store the license code
			add_post_meta($post_id, 'license', $wLicense);

			// store extra notes
			if ( $wExtraNotes ) add_post_meta($post_id, 'extra_notes', $wExtraNotes);
			
			// store notes for editor
			if ( $wNotes ) add_post_meta($post_id, 'editor_notes', $wNotes);

			// add the tags
			wp_set_post_tags( $post_id, $wTags);
		
			// set featured image
			set_post_thumbnail( $post_id, $wFeatureImageID);
							
			

			if ( trucollector_option('new_item_status') == 'publish' ) {
				// feed back for published item
				$feedback_msg = 'Your entry for <strong>' . $wTitle . '</strong> has been published!  You can <a href="'. wp_logout_url( site_url() . '/?p=' . $post_id  )  . '">view it now</a>. Or you can <a href="' . site_url() . '/collect">add another</a>.';
			
			} else {
				// feed back for item left in draft
				$feedback_msg = 'Your entry for <strong>' . $wTitle . '</strong> has been submitted as a draft  YYou can <a href="'. wp_logout_url( site_url() . '/?p=' . $post_id  )  . '">preview it now</a>. Once it has been approved by a moderator, everyone can see it.';	
			
			}		
			
			/*
			
			// Let's do some EMAIL!
		
			// who gets mail? They do.
			$to_recipients = explode( "," ,  trucollector_option( 'notify' ) );
		
			$subject = 'Review newly submitted writing at ' . get_bloginfo();
		
			$message = 'An image <strong>"' . $wTitle . '"</strong> shared by <strong>' . $wAuthor . '</strong>  has been submitted to ' . get_bloginfo() . '. You can <a href="'. site_url() . '/?p=' . $post_id . 'preview=true' . '">preview it now</a>.<br /><br /> To  publish it, simply <a href="' . admin_url( 'edit.php?post_status=pending&post_type=post') . '">find it in the submitted works</a> and change it\'s status from <strong>Draft</strong> to <strong>Publish</strong>';
		
			if ( $wNotes ) $message .= '<br /><br />There are some extra notes from the author:<blockquote>' . $wNotes . '</blockquote>';
		
			// turn on HTML mail
			add_filter( 'wp_mail_content_type', 'set_html_content_type' );
		
			// mail it!
			wp_mail( $to_recipients, $subject, $message);
		
			// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );	
			
			*/
				
			// set the gate	open, we are done.
			
			$is_published = true;
			$box_style = '<div class="notify notify-green"><span class="symbol icon-tick"></span> ';	
			
		} // count errors		
		
} // end form submmitted check
?>

<?php get_header(); ?>

<div class="content thin">		

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
	
		<div <?php post_class('post single'); ?>>
		
			<?php if ( has_post_thumbnail() ) : ?>
			
				<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail_size' ); $thumb_url = $thumb['0']; ?>
		
				<div class="featured-media">
		
					<?php the_post_thumbnail('post-image'); ?>
					
				</div> <!-- /featured-media -->
					
			<?php endif; ?>
			
			<div class="post-inner">
												
				<div class="post-header">
																										
					<h2 class="post-title"><?php the_title(); ?></h2>
															
				</div> <!-- /post-header section -->
				    
			    <div class="post-content">
			    
			    	<?php the_content(); ?>
	
			    	<?php 
					if ( !is_user_logged_in() ) :?>
						<a href="<?php echo get_bloginfo('url')?>/wp-login.php?autologin=writer">activate lasers</a>
					<?php endif?>
		    	
		    		<?php echo $box_style . $feedback_msg . '</div>';?>   
		    				
			    	<?php wp_link_pages('before=<div class="clear"></div><p class="page-links">' . __('Pages:','fukasawa') . ' &after=</p>&seperator= <span class="sep">/</span> '); ?>


	<?php if ( is_user_logged_in() and !$is_published ) : // show form if logged in and it has not been published ?>
			
		<form  id="collectorform" class="collectorform" method="post" action="" enctype="multipart/form-data">

				<fieldset>
					<label for="headerImage"><?php _e('Upload an Image', 'wpbootstrap') ?></label>
					
					<div class="uploader">
						<input id="wFeatureImage" name="wFeatureImage" type="hidden" value="<?php echo $wFeatureImage_id?>" />

						<img src="http://placehold.it/150x150" alt="uploaded image" id="featurethumb" /><br />
					
						<input type="button" id="wFeatureImage_button"  class="btn btn-success btn-medium  upload_image_button" name="_wImage_button"  data-uploader_title="Add a New Image" data-uploader_button_text="Select Image" value="Select Image" tabindex="1" />
						
						</div>
						
						<p>Upload your image by dragging it's icon to the window that opens when clicking  <strong>Select Image</strong> button. Larger JPG, PNG images are best, but to preserve animation, GIFs should be no larger than 500px wide.<br clear="left"></p>
					
				</fieldset>						


				<fieldset>
					<label for="wTitle"><?php _e('Title for the Image', 'wpbootstrap' ) ?></label><br />
					<p>An interesting title goes a long way; it's the headline.</p>
					<input type="text" name="wTitle" id="wTitle" class="required" value="<?php echo $wTitle; ?>" tabindex="2" />
				</fieldset>	
			

				<fieldset>
					<label for="wAuthor"><?php _e('Who is Uploading the Image?', 'wpbootstrap' ) ?></label><br />
					<p>Take credit for sharing with your name, twitter handle, secret agent name, or remain "Anonymous".</p>
					<input type="text" name="wAuthor" id="wAuthor" class="required" value="<?php echo $wAuthor; ?>" tabindex="3" />
				</fieldset>	
				
				<fieldset>
						<label for="wText"><?php _e('Caption', 'wpbootstrap') ?></label>
						<p>Enter a descriptive caption to include with the image. </p>
						<textarea name="wText" id="wText" rows="15"  tabindex="4"><?php echo stripslashes( $wText );?></textarea>

				</fieldset>
				
				<fieldset>
						<label for="wText"><?php _e('How was it found?', 'wpbootstrap') ?></label>
						<p>How did you find the image? What search words worked? What search tool did you use?</p>
						<textarea name="wExtraNotes" id="wExtraNotes" rows="15"  tabindex="4"><?php echo stripslashes( $wExtraNotes );?></textarea>
				</fieldset>
				
				
				<fieldset>
					<label for="wSource"><?php _e('Source of Image', 'wpbootstrap' ) ?></label><br />
					<p>If image is online, enter the URL for where you found it. Otherwise enter where it came from (e.g. 'Personal Photo'). Or leave blank.</p>
					<input type="text" name="wSource" id="wSource" class="required" value="<?php echo $wSource; ?>" tabindex="5" />
				</fieldset>					
				
				<fieldset>
					<label for="wCredit"><?php _e('Creator Name', 'wpbootstrap' ) ?></label><br />
					<p>Enter a name of a person, web site, etc to give credit for the image.</p>
					<input type="text" name="wCredit" id="wCredit" class="required" value="<?php echo $wCredit; ?>" tabindex="6" />
				</fieldset>					
				
				<fieldset>
					<label for="wLicense"><?php _e('License for Reuse', 'wpbootstrap' ) ?></label><br />
					<p>If found online, indicate the license attached to it. If this is an original image, then select a license to attach to it.</p>
					<select name="wLicense" id="wLicense" tabindex="7" />
					
					<?php
						foreach ($all_licenses as $key => $value) {
							$selected = ( $key == $wLicense ) ? ' selected' : '';
							echo '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
						}
					?>
					
					</select>
				</fieldset>					



				
				<fieldset>
					<label for="wCats"><?php _e( 'Categories', 'wpbootstrap' ) ?></label>
					<p>Check all that apply.</p>
					<?php 
					
					// set up arguments to get all categories that are children of "Published"
					$args = array(
						'hide_empty'               => 0,
					); 
					
					$article_cats = get_categories( $args );

					foreach ( $article_cats as $acat ) {
					
						$checked = ( in_array( $acat->term_id, $wCats) ) ? ' checked="checked"' : '';
						
						echo '<br /><input type="checkbox" name="wCats[]" tabindex="8" value="' . $acat->term_id . '"' . $checked . '> ' . $acat->name;
					}
					
					?>
					
				</fieldset>

				<fieldset>
					<label for="wTags"><?php _e( 'Tags', 'wpbootstrap' ) ?></label>
					<p>Descriptive tags, separate multiple ones with commas</p>
					
					<input type="text" name="wTags" id="wTags" value="<?php echo $wTags; ?>" tabindex="9"  />
				</fieldset>


				<fieldset>
						<label for="wNotes"><?php _e('Notes to the Editor', 'wpbootstrap') ?></label>						
						<p>Add any notes or messages to the site manager; this will not be part of what is published. If you want to be contacted, you will have to leave some means of contact.</p>
						<textarea name="wNotes" id="wNotes" rows="10"  tabindex="9"><?php echo stripslashes( $wNotes );?></textarea>
				</fieldset>

			
				<fieldset>
				
				<?php  wp_nonce_field( 'trucollector_form_make', 'trucollector_form_make_submitted' ); ?>
				
				<input type="submit" class="pretty-button pretty-button-green" value="Share Image" id="makeit" name="makeit" tabindex="12">
				</fieldset>
			
						
		</form>
	<?php endif?>
				    
			    </div>
	
			</div> <!-- /post-inner -->
			
		
		</div> <!-- /post -->
		
	<?php endwhile; else: ?>
	
		<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "fukasawa"); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
	
</div> <!-- /content -->
								
<?php get_footer(); ?>