<?php

if ( !is_user_logged_in() ) {
	// already not logged in? go to desk.
  	wp_redirect ( site_url('/') . 'desk' );
  	exit;
  	
} elseif ( !current_user_can( 'edit_others_posts' ) ) {
	// okay user, who are you? we know you are not an admin or editor
		
	// if the collector user not found, we send you to the desk
	if ( !trucollector_check_user() ) {
		// now go to the desk and check in properly
	  	wp_redirect ( site_url('/') . 'desk' );
  		exit;
  	}
}

		

// ------------------------ defaults ------------------------

// default welcome message
$feedback_msg = 'Add something to this collection? Yes! Use the form below to share it. Fields marked  <strong>*</strong> are required.';

$wTitle = '';
$wAuthor = 'Anonymous';
				
$wFeatureImageID = 0;
$wCats = array( trucollector_option('def_cat') ); // preload default category
$wLicense = '--'; // default license
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
 		$wText = 					wp_kses_post( $_POST['wText'] );
 		$wSource = 					sanitize_text_field( stripslashes( $_POST['wSource'] ) );
 		$wNotes = 					sanitize_text_field( stripslashes( $_POST['wNotes'] ) );
 		$wFeatureImageID = 			$_POST['wFeatureImage'];
 		$post_id = 					$_POST['post_id'];
 		$wCats = 					( isset ($_POST['wCats'] ) ) ? $_POST['wCats'] : array();
 		$wLicense = 				$_POST['wLicense'];
 		
 		
 		// let's do some validation, store an error message for each problem found
 		$errors = array();
 		
 		
 		if ( $wFeatureImageID == 0) $errors[] = '<strong>Image File Missing</strong> - upload the image you wish to add to represent this item.';
 		if ( $wTitle == '' ) $errors[] = '<strong>Title Missing</strong> - enter a descriptive title for this item.'; 
 		
 		if (  trucollector_option('use_caption') == '2' AND $wText == '' ) $errors[] = '<strong>Description Missing</strong> - please enter a detailed description for this utem.';
 
  		if (  trucollector_option('use_source') == '2' AND $wSource == '' ) $errors[] = '<strong>Source Missing</strong> - please the name or organization to credit as the source of this image.';
  		
  		if (  trucollector_option('use_license') == '2' AND $wLicense == '--' ) $errors[] = '<strong>License Not Selected</strong> - select an appropriate license for this item.'; 
		
 		 		
 		if ( count($errors) > 0 ) {
 			// form errors, build feedback string to display the errors
 			$feedback_msg = 'Sorry, but there are a few errors in your submission. Please correct and try again. We really want to add your item. <ul>';
 			
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
			if ( trucollector_option('use_source') > 0 ) {
				add_post_meta($post_id, 'source', $wSource);
			}
			
			// store the license code
			if ( trucollector_option('use_license') > 0 ) {
				add_post_meta($post_id, 'license', $wLicense);
			}
		
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
				$feedback_msg = 'Your entry for <strong>' . $wTitle . '</strong> has been submitted as a draft. You can <a href="'. wp_logout_url( site_url() . '/?p=' . $post_id  )  . '">preview it now</a>. Once it has been approved by a moderator, everyone can see it.';	
			
			}		
			
			
			if ( trucollector_option( 'notify' ) != '') {
			// Let's do some EMAIL!
		
				// who gets mail? They do.
				$to_recipients = explode( "," ,  trucollector_option( 'notify' ) );
		
				$subject = 'New item submitted to ' . get_bloginfo();
		
				if ( trucollector_option('new_item_status') == 'publish' ) {
					$message = 'An item <strong>"' . $wTitle . '"</strong> shared by <strong>' . $wAuthor . '</strong> has been published to ' . get_bloginfo() . '. You can <a href="'. site_url() . '/?p=' . $post_id  . '">see view it now</a>';
				

				} else {
					$message = 'An item <strong>"' . $wTitle . '"</strong> shared by <strong>' . $wAuthor . '</strong> has been submitted to ' . get_bloginfo() . '. You can <a href="'. site_url() . '/?p=' . $post_id . 'preview=true' . '">preview it now</a>.<br /><br /> To  publish it, simply <a href="' . admin_url( 'edit.php?post_status=draft&post_type=post') . '">find it in the drafts</a> and change it\'s status from <strong>Draft</strong> to <strong>Publish</strong>';
				}
				
				if ( $wNotes ) $message .= '<br /><br />There are some extra notes from the author:<blockquote>' . $wNotes . '</blockquote>';
		
				// turn on HTML mail
				add_filter( 'wp_mail_content_type', 'set_html_content_type' );
		
				// mail it!
				wp_mail( $to_recipients, $subject, $message);
		
				// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
				remove_filter( 'wp_mail_content_type', 'set_html_content_type' );	
			
				}
				
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
						<a href="<?php echo get_bloginfo('url')?>/wp-login.php?autologin=collector">activate lasers</a>
					<?php endif?>
		    	
		    		<?php echo $box_style . $feedback_msg . '</div>';?>   
		    				
			    	<?php wp_link_pages('before=<div class="clear"></div><p class="page-links">' . __('Pages:','fukasawa') . ' &after=</p>&seperator= <span class="sep">/</span> '); ?>


	<?php if ( is_user_logged_in() and !$is_published ) : // show form if logged in and it has not been published ?>
			
		<form  id="collectorform" class="collectorform" method="post" action="" enctype="multipart/form-data">
	
	
					<fieldset>
					<label for="wTitle"><?php _e('Title for this Item', 'fukasawa' ) ?> <strong>*</strong></label><br />
					<p>Enter a descriptive title that works well as a headline when listed in this site.</p>
					<input type="text" name="wTitle" id="wTitle" class="required" value="<?php echo $wTitle; ?>" tabindex="1" />
				</fieldset>	
			
				

				<fieldset>
					<label for="headerImage"><?php _e('Upload an Image for this Item', 'fukasawa') ?> <strong>*</strong></label>
					
					<div class="uploader">
						<input id="wFeatureImage" name="wFeatureImage" type="hidden" value="<?php echo $wFeatureImageID?>" />

						<?php if ( $wFeatureImageID ):
							 echo wp_get_attachment_image( $wFeatureImageID, 'thumbnail' );
						?>
						
						<?php else:?>
						
						<img src="https://placehold.it/150x150" alt="uploaded image" id="featurethumb" />
						
						<?php endif?>
						
						
						
						<br />
					
						<input type="button" id="wFeatureImage_button"  class="btn btn-success btn-medium  upload_image_button" name="_wImage_button"  data-uploader_title="Add a New Image" data-uploader_button_text="Select Image" value="Select Image" tabindex="2" />
						
						</div>
						
						<p>Upload an image by dragging its icon to the window that opens when clicking  <strong>Select Image</strong> button. Larger JPG, PNG images are best; to preserve animation, GIFs should be no larger than 500px wide.<br clear="left"></p>
					
				</fieldset>						




				<fieldset>
					<label for="wAuthor"><?php _e('Who is Uploading the Image?', 'fukasawa' ) ?></label><br />
					<p>Take credit for sharing this item by entering your name(s),  twitter handle(s), or remain "Anonymous".</p>
					<input type="text" name="wAuthor" id="wAuthor" class="required" value="<?php echo $wAuthor; ?>" tabindex="3" />
				</fieldset>	
  		
  				
  				<?php if (  trucollector_option('use_caption') > '0'):	
  					$required = (trucollector_option('use_caption') == 2) ? '<strong>*</strong>' : '';
  				?>
  						
					<fieldset>
							<label for="wText"><?php _e('Item Description', 'fukasawa') ?> <?php echo $required?> </label>
							<p><?php echo  trucollector_option('caption_prompt')?> </p>
							
							<?php if (  trucollector_option('caption_field') == 's'):?>	
							<textarea name="wText" id="wText" rows="4"  tabindex="4"><?php echo stripslashes( $wText );?></textarea><p style="font-size:0.8rem">To create hyperlinks use this shortcode<br /><code>[link url="http://www.themostamazingwebsiteontheinternet.com/" text="the coolest site on the internet"]</code><br />If you omit <code>text=</code> the URL will be the link text.</p>
							
							<?php else:?>
							
							<?php
							// set up for inserting the WP post editor
							$settings = array( 'textarea_name' => 'wText', 'editor_height' => '300',  'tabindex'  => "5", 'media_buttons' => false);

							wp_editor(  stripslashes( $wText ), 'wtext', $settings );
							
							?>
							
							
							<?php endif?>

					</fieldset>	
				
				<?php endif?>			


  				<?php if (  trucollector_option('use_source') > '0'):	
  					$required = (trucollector_option('use_source') == 2) ? '<strong>*</strong>' : '';
  				?>
				
					<fieldset>
						<label for="wSource"><?php _e('Source of Image', 'fukasawa' ) ?> <?php echo $required?></label> 
						<p>Enter name of a person, web site, etc to give credit for the image submitted above.</p>
						<input type="text" name="wSource" id="wSource" class="required" value="<?php echo $wSource; ?>" tabindex="6" />
				</fieldset>		
				
				<?php endif?>	
				
  				<?php if (  trucollector_option('use_license') > '0'):	
  					$required = (trucollector_option('use_license') == 2) ? '<strong>*</strong>' : '';
  				?>
							
				
					<fieldset>
						<label for="wLicense"><?php _e('License for Reuse', 'fukasawa' ) ?> <?php echo $required?></label>
						<p>Indicate a reuse license associated with the image. If this is your own image,  select a license to share it under.</p>
						<select name="wLicense" id="wLicense" tabindex="7" />
						<option value="--">Select a License</option>
					
						<?php
							foreach ($all_licenses as $key => $value) {
								$selected = ( $key == $wLicense ) ? ' selected' : '';
								echo '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
							}
						?>
					
						</select>
					</fieldset>					

				<?php endif?>	

				
				<fieldset>
					<label for="wCats"><?php _e( 'Categories', 'fukasawa' ) ?></label>
					<p>Check all categories that will help organize this item.</p>
					<?php 
					
					// arguments for request of categories
					$args = array(
						'hide_empty' => 0,
					); 
					
					$article_cats = get_categories( $args );

					foreach ( $article_cats as $acat ) {
					
						$checked = ( in_array( $acat->term_id, $wCats) ) ? ' checked="checked"' : '';
						
						echo '<br /><input type="checkbox" name="wCats[]" tabindex="8" value="' . $acat->term_id . '"' . $checked . '> ' . $acat->name;
					}
					
					?>
					
				</fieldset>

				<fieldset>
					<label for="wTags"><?php _e( 'Tags', 'fukasawa' ) ?></label>
					<p>Add any descriptive tags for this item. Separate multiple ones with commas.</p>
					
					<input type="text" name="wTags" id="wTags" value="<?php echo $wTags; ?>" tabindex="9"  />
				</fieldset>


				<fieldset>
						<label for="wNotes"><?php _e('Notes to the Editor', 'fukasawa') ?></label>						
						<p>Add any notes or messages to send to the site manager; this will not be part of what is published. If you wish to be contacted, leave an email address or twitter handle.</p>
						<textarea name="wNotes" id="wNotes" rows="10"  tabindex="9"><?php echo stripslashes( $wNotes );?></textarea>
				</fieldset>

			
				<fieldset>
								
				
				<?php  wp_nonce_field( 'trucollector_form_make', 'trucollector_form_make_submitted' ); ?>

				<input type="submit" value="Share This Item" id="makeit" name="makeit" tabindex="12">
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