<?php

/*
Template Name: Add to Collection
*/

// ------------------------ defaults ------------------------

// default welcome message
$feedback_msg = trucollector_form_default_prompt() . ' Fields marked  <strong>*</strong> are required.';

// blank defaults

$wTitle = $wText = $wSource = $wTags = $wNotes = $wEmail = '';
$wAuthor = 'Anonymous';
				
$wFeatureImageID = 0;
$wFeatureImageUrl =  get_stylesheet_directory_uri() . '/images/splot-test-drive.jpg';
$wCats = array( trucollector_option('def_cat') ); // preload default category
$wLicense = '--'; // default license
$all_licenses = trucollector_get_licences();
$is_re_edit = false;


// not yet saved
$is_published = false;
$box_style = '<div class="notify"><span class="symbol icon-info"></span> ';


// ------------------------ front gate ------------------------
	
// check for query vars that indicate this is a edit request
$tk  = get_query_var( 'tk', 0 );    // magic token to check

if ( ( $tk )  ) {
	// re-edit attempt
	$is_re_edit = true;
	
	// log in as author
	if ( !is_user_logged_in() ) {
		splot_user_login( 'collector', false );
	}
	
	$wid = trucollector_get_id_from_tk( $tk );
	
	if ($wid) {
		// found a post with the matching code, so set up for re-edit
		
		// default welcome message for a re-edit
		$feedback_msg = trucollector_form_re_edit_prompt();

		// get the post and then content for this item
		$item = get_post( $wid );
		$wText = $item->post_content; 

		$wTitle = get_the_title( $wid );
		$wAuthor =  get_post_meta( $wid, 'shared_by', 1 );
		
		$wEmail =  get_post_meta( $wid, 'wEmail', 1 );

		$box_style = '<div class="notify notify-green"><span class="symbol icon-tick"></span> ';	

		// get categories
		$categories = get_the_category( $wid);
		foreach ( $categories as $category ) { 
			$wCats[] = $category->term_id;
		}
		
		// festured image
		$wFeatureImageID = get_post_thumbnail_id( $wid );
		
		// url for preview
		$wFeatureImageUrl = get_the_post_thumbnail_url( $wid, 'post-image' );
		
		// source
		$wSource = get_post_meta( $wid, 'source', 1 );

		// notes
		$wNotes = get_post_meta( $wid, 'editor_notes', 1 );

		// license
		$wLicense = get_post_meta( $wid, 'license', 1 );

		// load the tags
		$wTags = implode(', ', wp_get_post_tags( $wid, array( 'fields' => 'names' ) ) );
				
		// post id
		$post_id = $wid;
	
	} else {
		// no posts found with matching key
	
		$is_re_edit = false;

		// updates for display	
		$errors[] = '<strong>Token Mismatch</strong> - please check the url provided.';
		// default welcome message
		$feedback_msg = 'This URL does not match the edit key. Please check the link from your email again, or return to your published writing and click the button at the bottom to send an edit link.';
		$is_published = true;  // not really but it serves to hide the form.
		$box_style = '<div class="notify notify-red"><span class="symbol icon-error"></span> ';
	}
} 




// ------------------- form processing ------------------------

// verify that a form was submitted and it passes the nonce check
if ( isset( $_POST['trucollector_form_make_submitted'] ) && wp_verify_nonce( $_POST['trucollector_form_make_submitted'], 'trucollector_form_make' ) ) {
 
 		// grab the variables from the form
 		$wTitle = 					sanitize_text_field( stripslashes( $_POST['wTitle'] ) );
 		$wAuthor = 					( isset ($_POST['wAuthor'] ) ) ? sanitize_text_field( stripslashes($_POST['wAuthor']) ) : 'Anonymous';		
 		$wTags = 					sanitize_text_field( $_POST['wTags'] );	
 		$wEmail = 					sanitize_text_field( $_POST['wEmail'] );	
 		$wText = 					wp_kses_post( $_POST['wText'] );
 		$wSource = 					sanitize_text_field( stripslashes( $_POST['wSource'] ) );
 		$wNotes = 					sanitize_text_field( stripslashes( $_POST['wNotes'] ) );
 		$wFeatureImageID = 			$_POST['wFeatureImage'];
 		if ( isset ($_POST['post_id'] ) ) $post_id = $_POST['post_id'];
 		$wCats = 					( isset ($_POST['wCats'] ) ) ? $_POST['wCats'] : array();
 		$wLicense = 				$_POST['wLicense'];
 		
 		
 		// let's do some validation, store an error message for each problem found
 		$errors = array();
 		
 		
 		if ( $wFeatureImageID == 0) $errors[] = '<strong>Image File Missing</strong> - upload the image you wish to add to represent this item.';
 		if ( $wTitle == '' ) $errors[] = '<strong>Title Missing</strong> - enter a descriptive title for this item.'; 
 		
 		if (  trucollector_option('use_caption') == '2' AND $wText == '' ) $errors[] = '<strong>Description Missing</strong> - please enter a detailed description for this utem.';
 
  		if (  trucollector_option('use_source') == '2' AND $wSource == '' ) $errors[] = '<strong>Source Missing</strong> - please the name or organization to credit as the source of this image.';
  		
  		if (  trucollector_option('use_license') == '2' AND $wLicense == '--' ) $errors[] = '<strong>License Not Selected</strong> - select an appropriate license for this item.'; 

		// test for email only if enabled in options
		if ( trucollector_option('show_email') )   {
		
			// check first for valid email address, blank is ok
			if ( is_email( $wEmail ) OR empty($wEmail) ) {

				// if email is good then check if we are limiting to domains
				if ( !empty(trucollector_option('email_domains'))  AND !trucollector_allowed_email_domain( $wEmail ) ) {
					$errors[] = '<strong>Email Address Not Allowed</strong> - The email address you entered <code>' . $wEmail . '</code> is not from an domain accepted in this site. This site requests that addresses are ones from domain[s] <code>' .  trucollector_option('email_domains') . '</code>. ';
				}
		
			} else {
				// bad email, sam.
				$errors[] = '<strong>Invalid Email Address</strong> - the email address entered <code>' . $wEmail . '</code> is not a valid address. To skip entering an email address, make sure the field is empty. Pleae check and try again. ';
			}
		}
		
 		 		
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
 			$w_information = array(
				'post_title' => $wTitle,
				'post_content' => $wText,
				'post_category' => $wCats		
			);
			
	
 			
 			if 	($is_re_edit) {
 			// update an existing post
 			
 				$w_information['post_status'] =  'publish';
 				$w_information['ID'] = $post_id;
 				
 				// update the post
				wp_update_post( $w_information );
				
				// update the tags
				wp_set_post_tags( $post_id, $wTags);

				// store the author as post meta data
				update_post_meta($post_id, 'shared_by', $wAuthor);
			
				// store the email as post meta data
				update_post_meta($post_id, 'wEmail', $wEmail);				
				
				// store the source of the image (text or URL)
				if ( trucollector_option('use_source') > 0 ) {
					update_post_meta($post_id, 'source', $wSource);
				}
				
				// update featured image
				set_post_thumbnail( $post_id, $wFeatureImageID);

			
				// store the license code
				if ( trucollector_option('use_license') > 0 ) {
					update_post_meta($post_id, 'license', $wLicense);
				}
		
				// store notes for editor
				if ( $wNotes ) update_post_meta($post_id, 'editor_notes', $wNotes);

				// add the tags
				wp_set_post_tags( $post_id, $wTags);
				
				// update edit key
				trucollector_make_edit_link( $post_id );

 				
 				$feedback_msg = 'Your entry for <strong>"' . $wTitle . '"</strong> has been updated!  You can <a href="'. get_permalink( $post_id ) . '">review it now</a>  or <a href="' . site_url()  . '">return to ' . get_bloginfo() . '</a>.';
 			
 			} else {
 			// good enough, let's make a new post! 
 				$w_information['post_status'] =  trucollector_option('new_item_status');
 				
 				// insert as a new post
				$post_id = wp_insert_post( $w_information );
			
				// store the author as post meta data
				add_post_meta($post_id, 'shared_by', $wAuthor);
			
				// store the email as post meta data
				add_post_meta($post_id, 'wEmail', $wEmail);				
			

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
			
				// create an edit key
				trucollector_make_edit_link( $post_id );
			
				if  ( trucollector_option('new_item_status') == 'publish' ) {
					// feed back for published item
					$feedback_msg = 'Your entry for <strong>"' . $wTitle . '"</strong> has been published. ';
					
					// if user provided email address (only possible if the feature enabled), send instructions to use link to edit
					
					if ( $wEmail != '' ) {
						trucollector_mail_edit_link( $post_id, 'publish' );
						$feedback_msg .= 'Since you provided an email address, a message has been sent to <strong>' . $wEmail . '</strong>  with a special link that can be used at any time later to edit this item. '; 
					}
					
					 $feedback_msg .= 'You can <a href="'. get_permalink( $post_id ) . '">view it now</a>  or <a href="' . site_url()  . '">return to ' . get_bloginfo() . '</a>.';
			
				} else {
					// feed back for item left in draft
					$feedback_msg = 'Your entry for <strong>"' . $wTitle . '"</strong> has been submitted as a draft. Once it has been approved by a moderator, everyone will be able to view it. ';
					
					// if user provided email address (only possible if the feature enabled), send instructions to use link to edit
					if ( $wEmail != '' ) {

						$feedback_msg .= 'Since you provided an email address, after it is published, you will see a special button you can use to have a special link sent by email that can be used to edit this item later. '; 
					}

					$feedback_msg .= 'For now, you can <a href="' . site_url()  . '">return to ' . get_bloginfo() . '</a>.';	
			
				}	

			if ( trucollector_option( 'notify' ) != '') {
			// Let's do some EMAIL!
		
				// who gets mail? They do.
				$to_recipients = explode( "," ,  trucollector_option( 'notify' ) );
		
				$subject = 'New item submitted to ' . get_bloginfo();
		
				if ( trucollector_option('new_item_status') == 'publish' ) {
					$message = 'An item <strong>"' . $wTitle . '"</strong> shared by <strong>' . $wAuthor . '</strong> has been published to ' . get_bloginfo() . '. You can <a href="'. site_url() . '/?p=' . $post_id  . '">view it now</a>';
				

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
 			}

			// logout the special user
			
			if ( trucollector_check_user()=== true ) wp_logout();
				
			// set the gate	open, we are done.
			
			$is_published = true;
			$box_style = '<div class="notify notify-green"><span class="symbol icon-tick"></span> ';	
			
		} // count errors		
		
} // end form submmitted check
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
	
	
					<fieldset id="theTitle">
					<label for="wTitle"><?php trucollector_form_item_title() ?> <strong>*</strong></label><br />
					<p><?php trucollector_form_item_title_prompt() ?> </p>
					<input type="text" name="wTitle" id="wTitle" class="required" value="<?php echo $wTitle; ?>" tabindex="1" />
				</fieldset>	
			
				

				<fieldset id="theHeaderImage">
					<label for="headerImage"><?php trucollector_form_item_upload() ?> <strong>*</strong></label>
					
					<div class="uploader">
						<input id="wFeatureImage" name="wFeatureImage" type="hidden" value="<?php echo $wFeatureImageID?>" />
						<input id="wFeatureImageUrl" type="hidden" value="<?php echo $wFeatureImageUrl ?>">

						<?php if ( $wFeatureImageID ):
							 echo wp_get_attachment_image( $wFeatureImageID, 'thumbnail', "", array( "id" => "featurethumb" )  );
						?>
						
						<?php else:?>
						
							<img src="https://placehold.it/150x150?text=Upload+Image" alt="upload image" id="featurethumb" />
						
						
						<?php endif?>
						
						
						
						<br />
					
						<input type="button" id="wFeatureImage_button"  class="btn btn-success btn-medium  upload_image_button" name="_wImage_button"  data-uploader_title="Add a New Image" data-uploader_button_text="Select Image" value="Select Image" tabindex="2" />
						
						</div>
						
						<p><?php trucollector_form_item_upload_prompt() ?><br clear="left"></p>
					
				</fieldset>						




				<fieldset id="theAuthor">
					<label for="wAuthor"><?php trucollector_form_item_author()?></label><br />
					<p><?php trucollector_form_item_author_prompt()?></p>
					<input type="text" name="wAuthor" id="wAuthor" class="required" value="<?php echo $wAuthor; ?>" tabindex="3" />
				</fieldset>	
  		
  				
  				<?php if (  trucollector_option('use_caption') > '0'):	
  					$required = (trucollector_option('use_caption') == 2) ? '<strong>*</strong>' : '';
  				?>
  						
					<fieldset id="theText">
							<label for="wText"><?php trucollector_form_item_description() ?> <?php echo $required?> </label>
							<p><?php trucollector_form_item_description_prompt()?> </p>
							
							<?php if (  trucollector_option('caption_field') == 's'):?>	
							
							<input id="wRichText" type="hidden" value="0">
							
							<textarea name="wText" id="wText" rows="4"  tabindex="4"><?php echo stripslashes( $wText );?></textarea><p style="font-size:0.8rem">To create hyperlinks use this shortcode<br /><code>[link url="http://www.themostamazingwebsiteontheinternet.com/" text="the coolest site on the internet"]</code><br />If you omit <code>text=</code> the URL will be the link text.</p>
							
							<?php else:?>
							
							
							<input id="wRichText" type="hidden" value="1">
							
							<?php
							// set up for inserting the WP post editor
							$settings = array( 'textarea_name' => 'wText', 'editor_height' => '300',  'tabindex'  => "5", 'media_buttons' => false, 'drag_drop_upload' => true);

							wp_editor(  stripslashes( $wText ), 'wTextHTML', $settings );
							
							?>
							
							
							<?php endif?>

					</fieldset>	
				
				<?php endif?>			


  				<?php if (  trucollector_option('use_source') > '0'):	
  					$required = (trucollector_option('use_source') == 2) ? '<strong>*</strong>' : '';
  				?>
				
					<fieldset id="theSource">
						<label for="wSource"><?php trucollector_form_item_image_source() ?> <?php echo $required?></label> 
						<p><?php trucollector_form_item_image_source_prompt() ?></p>
						<input type="text" name="wSource" id="wSource" class="required" value="<?php echo $wSource; ?>" tabindex="6" />
					</fieldset>		
				
				<?php endif?>	
				
  				<?php if (  trucollector_option('use_license') > '0'):	
  					$required = (trucollector_option('use_license') == 2) ? '<strong>*</strong>' : '';
  				?>
							
				
					<fieldset  id="theLicense">
						<label for="wLicense"><?php trucollector_form_item_license() ?> <?php echo $required?></label>
						<p><?php trucollector_form_item_license_prompt() ?></p>
						
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
				
				
				<?php if ( trucollector_option( 'show_attribution' ) == 1 ): ?>
					<input id="wAttributionPreview" type="hidden" value="1">
				<?php endif?>
				
				
				<?php if (trucollector_option('show_cats') ):?>
				
					<fieldset  id="theCats">
						<label for="wCats"><?php trucollector_form_item_categories() ?></label>
						<p><?php trucollector_form_item_categories_prompt() ?></p>
						<?php 
					
						// arguments for request of categories
						$args = array(
							'hide_empty' => 0,
						); 
					
						$article_cats = get_categories( $args );

						foreach ( $article_cats as $acat ) {
					
							$checked = ( in_array( $acat->term_id, $wCats) ) ? ' checked="checked"' : '';
						
							echo '<label><input type="checkbox" name="wCats[]" tabindex="8" value="' . $acat->term_id . '"' . $checked . '> ' . $acat->name . '</label><br />';
						}
					
						?>
					
					</fieldset>
				
				<?php endif?>
				
				
				<?php if (trucollector_option('show_tags') ):?>
					<fieldset  id="theTags">
						<label for="wTags"><?php  trucollector_form_item_tags() ?></label>
						<p><?php  trucollector_form_item_tags_prompt() ?></p>
					
						<input type="text" name="wTags" id="wTags" value="<?php echo $wTags; ?>" tabindex="9"  />
					</fieldset>
				<?php endif?>



				<?php if (trucollector_option('show_email') ):?>
				<fieldset id="theEmail">
					<label for="wEmail"><?php trucollector_form_item_email() ?> (optional)</label><br />
					<p><?php trucollector_form_item_email_prompt() ?> 
					<?php 
						if  ( !empty( trucollector_option('email_domains') ) ) {
							echo ' Allowable email addresses must be ones from domains <code>' . trucollector_option('email_domains') . '</code>.';
						}
					?>
					
					</p>
					<input type="text" name="wEmail" id="wEmail" name="wEmail"  value="<?php echo $wEmail; ?>" />
				</fieldset>	
				
				<?php endif?>				
				
				
				<?php if (trucollector_option('show_notes') ):?>
					<fieldset  id="theNotes">
							<label for="wNotes"><?php trucollector_form_item_editor_notes() ?></label>						
							<p><?php trucollector_form_item_editor_notes_prompt() ?></p>
						
							<textarea name="wNotes" id="wNotes" rows="10"  tabindex="9"><?php echo stripslashes( $wNotes );?></textarea>
					</fieldset>
				<?php endif?>
			
				<fieldset id="theButtons">			
					<label for="theButtons"><?php trucollector_form_item_submit_buttons() ?></label>	
					<?php  wp_nonce_field( 'trucollector_form_make', 'trucollector_form_make_submitted' ); ?>
					
					<p><?php trucollector_form_item_submit_buttons_prompt() ?></p>
					
					
					
					<a href="#preview" class="fancybox" title="Preview of your item. Close this overlay, make any changes, than click 'Share It'">Preview First</a>
					
					<?php $submitbutton = ($is_re_edit) ? "Update Now" : "Share Now";?>
					
					<input type="submit" value="<?php echo $submitbutton?>" id="makeit" name="makeit" tabindex="12">
					
				</fieldset>
			
						
		</form>
	<?php endif?>
				    
			    </div>
	
			</div> <!-- /post-inner -->
			
		
			</div><!-- .post -->
																
			<?php 
		endwhile; 

	endif; 
	
	?>

</div><!-- .content -->
		
<?php get_footer(); ?>