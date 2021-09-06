<?php

/*
Template Name: Add to Collection
*/

// set blanks
$wTitle =  $wSource = $wTags = $wNotes = $wEmail = $wAlt = $w_thumb_status = '';
$wFeatureImageID = $wCommentNotify = $post_id =  0;
$is_re_edit = $linkEmailed = $wAccessCodeOk = $is_published = false;
$errors = array();

// default welcome message
$feedback_msg = trucollector_form_default_prompt();
$wAuthor = 'Anonymous';

// initial button states
$previewBtnState = ' disabled';
$submitBtnState = ' disabled';
$box_style = '<div class="notify"><span class="symbol icon-info"></span> ';

$wCats = array( trucollector_option('def_cat') ); // preload default category
$wText = trucollector_option('def_text'); // default text for editing field
$wAltRequired = trucollector_option('img_alt');
$wLicense = '--'; // default license
$all_licenses = trucollector_get_licences();

// see if we have an incoming clear the code form variable only on writing form
// ignored if options are not to use it or we are in the customizer

$wAccessCodeOk = isset( $_POST['wAccessCodeOk'] ) ? true : (is_customize_preview()) ? true : false;

// check that an access code is in play and it's not been yet passed
if ( !empty( trucollector_option('accesscode') ) AND !$wAccessCodeOk ) {

	// now see if we are to check the access code
	if ( isset( $_POST['trucollector_form_access_submitted'] )
	  AND wp_verify_nonce( $_POST['trucollector_form_access_submitted'], 'trucollector_form_access' ) ) {

	   // grab the entered code from  form
		$wAccess = 	stripslashes( $_POST['wAccess'] );

		// Validation of the code
		if ( $wAccess != trucollector_option('accesscode') ) {
			$box_style = '<div class="notify notify-red"><span class="symbol icon-error"></span> ';
			$feedback_msg = '<strong>Incorrect Access Code</strong> - try again? Hint: ' . trucollector_option('accesshint') . '.';
		} else {
			$wAccessCodeOk = true;
		}
	} else {
		$box_style = '<div class="notify"><span class="symbol icon-info"></span> ';
		$feedback_msg = 'An access code is required to use the collection form on "' . get_bloginfo('name') . '".';
	} // form check access code
} else {
	// set flag true just to clear all the other gates
	$wAccessCodeOk = true;
} // access code in  play check



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

 		$wFeatureImageID =			( isset ( $_POST['wFeatureImage'] ) ) ? $_POST['wFeatureImage'] : 0;

 		$wAlt = 					( isset ($_POST['wAlt'] ) ) ? sanitize_text_field($_POST['wAlt']) : '';

 		$wCats = 					( isset ($_POST['wCats'] ) ) ? $_POST['wCats'] : array( trucollector_option('def_cat') );
 		$wLicense = 				( isset ( $_POST['wLicense'] ) ) ? $_POST['wLicense'] : '';

 		$wCommentNotify = 			( isset ( $_POST['wCommentNotify'] ) ) ? 1 : 0;

 		if ( isset ($_POST['post_id'] ) ) $post_id = $_POST['post_id'];

		// upload header image if we got one
		if ($_FILES) {

			foreach ( $_FILES as $file => $array ) {
				$newupload = trucollector_insert_attachment( $file, $post_id );
				if ( $newupload ) {
					$wFeatureImageID = $newupload;
					$w_thumb_status = 'Image uploaded. Choose another to replace it.';

				}
			}
		}


		 if ( $wTitle == '' ) $errors[] = '<strong>Title Missing</strong> - enter a descriptive title for this ' . get_trucollector_collection_single_item() . '.';

		// do we have image?
 		if ( $wFeatureImageID == 0) {
 			$errors[] = '<strong>Image File Missing</strong> - upload the image you wish to add to represent this ' . get_trucollector_collection_single_item() . '.';
 		}

 		 if (  trucollector_option('img_alt') == '1' AND $wAlt == '' ) $errors[] = '<strong>Image Alternative Text Missing</strong> - please enter text description to make your image accessible for visually impaired visitors.';

 		if (  trucollector_option('use_caption') == '2' AND $wText == '' ) $errors[] = '<strong>Description Missing</strong> - please enter a detailed description for this ' . get_trucollector_collection_single_item() . '.';

  		if (  trucollector_option('use_source') == '2' AND $wSource == '' ) $errors[] = '<strong>Source Missing</strong> - please the name or organization to credit as the source of this image.';

  		if (  trucollector_option('use_license') == '2' AND $wLicense == '--' ) $errors[] = '<strong>No License Selected</strong> - select an appropriate license for this ' . get_trucollector_collection_single_item() . '.';

		// test for email only if enabled in options
		if ( trucollector_option('show_email') == '1' )   {

			// check first for valid email address, blank is ok
			if ( is_email( $wEmail ) OR empty($wEmail) ) {

				// if email is good then check if we are limiting to domains
				if ( !empty(trucollector_option('email_domains'))  AND !trucollector_allowed_email_domain( $wEmail ) ) {
					$errors[] = '<strong>Email Address Not Allowed</strong> - The email address you entered <code>' . $wEmail . '</code> is not from a domain accepted for this site. This site requests that addresses are ones from domain[s] <code>' .  trucollector_option('email_domains') . '</code>. ';
					}

				} else {
					// bad email, sam.
					$errors[] = '<strong>Invalid Email Address</strong> - the email address entered <code>' . $wEmail . '</code> is not valid. Pleae check and try again. To skip entering an email address, make sure the field is empty. ';
				}

		} elseif ( trucollector_option('show_email') == '2' )  {
			// now test for case where email is required

			if (empty( $wEmail ) ) {
				// ding ding, no email
				$errors[] = '<strong>Email Address Missing</strong> - an email address is required for this site. Please enter one.';

			} elseif ( is_email( $wEmail ) ) {

				// if email is good then check if we are limiting to domains
				if ( !empty(trucollector_option('email_domains'))  AND !trucollector_allowed_email_domain( $wEmail ) ) {
					$errors[] = '<strong>Email Address Not Allowed</strong> - The email address you entered <code>' . $wEmail . '</code> is not from an domain accepted in this site. This site requests that addresses are ones with domains <code>' .  trucollector_option('email_domains') . '</code>. ';
				}

			}  else {
			// bad email, sam.
			$errors[] = '<strong>Invalid Email Address</strong> - the email address entered <code>' . $wEmail . '</code> is not valid. Pleae check and try again.';
			}

		}

 		if ( count($errors) > 0 ) {
 			// form errors, build feedback string to display the errors
 			$feedback_msg = 'Sorry, but there are a few errors in your submission. Please correct and try again. We really want to add your ' . get_trucollector_collection_single_item() . '.<ul>';

 			// Hah, each one is an oops, get it?
 			foreach ($errors as $oops) {
 				$feedback_msg .= '<li>' . $oops . '</li>';
 			}

 			$feedback_msg .= '</ul>';

 			// reset button states
			$previewBtnState = ' disabled';
			$submitBtnState = ' disabled';


 			$box_style = '<div class="notify notify-red"><span class="symbol icon-error"></span> ';

 		} else { // good enough, let's set up a post!


			$post_status = trucollector_option('new_item_status');
			if ($post_status == 'draft') $post_status = 'pending'; // fix wrong status from older versions

			if ( isset( $_POST['makeit'] ) ) {

				// set status (will be either 'publish' or 'pending') for post based on theme settings
				$post_status = trucollector_option('new_item_status');


				$is_published = true;
				$box_style = '<div class="notify"><span class="symbol icon-info"></span> ';

				// set up notifications, email messages for later use
				if  ( $post_status == 'publish' ) {
					// feed back for published item


					// feed back for published item
					$feedback_msg = 'Your ' . get_trucollector_collection_single_item()  . '  "' . $wTitle . '" has been published! ';


					// if user provided email address (only possible if the feature enabled), send instructions to use link to edit

					if ( $wEmail != '' ) {
						$feedback_msg .= 'Since you provided an email address, a message has been sent to <strong>' . $wEmail . '</strong>  with a special link that can be used at any time later to edit this ' . get_trucollector_collection_single_item() . '. ';
					} else {
						$feedback_msg .=  ' You might want to save this link <code>' .  trucollector_get_edit_link( $post_id ) . '</code> in a safe place as it allows you to edit your ' . get_trucollector_collection_single_item() . ' at a later time. ';
					} // wEmail != ''

					 $feedback_msg .= 'You can <a href="'. get_permalink( $post_id ) . '">view it now</a>  or <a href="' . site_url()  . '">return to ' . get_bloginfo() . '</a>.';

					 // for email
					$message = 'A new ' . get_trucollector_collection_single_item() . ' <strong>"' . $wTitle . '"</strong> written by <strong>' . $wAuthor . '</strong> has been published to ' . get_bloginfo() . '. You can <a href="'. site_url() . '/?p=' . $post_id  . '">view it now</a>';


				} elseif ( $post_status == 'pending' ) {

					$feedback_msg = 'Your ' . get_trucollector_collection_single_item()  . '  "' . $wTitle . '" is now in the queue for publishing. ';


					if ( $wEmail != '' ) {
						$feedback_msg .= 'Since you provided an email address, a message has been sent to <strong>' . $wEmail . '</strong>  with a special link that can be used at any time later to edit this ' . get_trucollector_collection_single_item() . '. ';

					} else {
						$feedback_msg .=  ' You might want to save this link <code>' .  trucollector_get_edit_link( $post_id ) . '</code> in a safe place as it allows you to edit your ' . get_trucollector_collection_single_item() . ' at a later time. ';
					} // wEmail != ''

					$feedback_msg .= 'You can <a href="'. site_url() . '/?p=' . $post_id . '&preview=true&ispre=1' . '"  target="_blank">preview it now</a> (link opens in a new window). It will appear on <strong>' . get_bloginfo() . '</strong> as soon as it has been reviewed. Now you can <a href="' . site_url()  . '">return to ' . get_bloginfo() . '</a>.';

					$message = 'A new ' . get_trucollector_collection_single_item() . ' <strong>"' . $wTitle . '"</strong> written by <strong>' . $wAuthor . '</strong> has been submitted to ' . get_bloginfo() . '. You can <a href="'. site_url() . '/?p=' . $post_id . 'preview=true' . '">preview it now</a>.<br /><br /> To publish it, simply <a href="' . admin_url( 'edit.php?post_status=pending&post_type=post') . '">find it in the pending items</a> and change its status from <strong>Pending</strong> to <strong>Publish</strong>';

				} // post_status == 'publish / pending'

			} else {
			// updated button clicked
				$post_status = 'draft';
				$box_style = '<div class="notify notify-green"><span class="symbol icon-tick"></span> ';

				$feedback_msg = 'Your draft has been updated and can again be <a href="'. site_url() . '/?p=' . $post_id . '&preview=true&ispre=1' . '" target="_blank">previewed</a> to review changes. When ready to publish <a href="#theButtons">just scroll down</a> and click "Share Now" to add it to' . get_bloginfo( 'name' ) . '.';

				// enable preview and submit buttons
				$previewBtnState = '';
				$submitBtnState = '';

			} // isset( $_POST['makeit'] )

		$w_information = array(
			'post_title' => $wTitle,
			'post_content' => $wText,
			'post_category' => $wCats,
			'post_status' => $post_status
		);

		// Is this a first draft?
		if ( $post_id == 0 ) {

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


			// track the comment notification preference
			if ( trucollector_option( 'allow_comments' )  ) add_post_meta($post_id, 'wCommentNotify',  $wCommentNotify);

			// add the tags
			wp_set_post_tags( $post_id, $wTags);

			// set featured image
			set_post_thumbnail( $post_id, $wFeatureImageID);

			// update featured image alt
			update_post_meta($wFeatureImageID, '_wp_attachment_image_alt', $wAlt);

			// create an edit key
			trucollector_make_edit_link( $post_id );

			if ( $wEmail != '' ) {
				trucollector_mail_edit_link( $post_id, 'draft' );
				$linkEmailed = true;
			}

			// feedback for first check of item
			$feedback_msg = 'A draft of your ' . get_trucollector_collection_single_item() . ' has been saved. You can <a href="'. site_url() . '/?p=' . $post_id . '&preview=true&ispre=1' . '" target="_blank">preview it now</a>. This will open in a new tab/window. Or make any changes below, check your information again and/or <a href="#theButtons">scroll down</a> to submit it to ' . get_bloginfo() . '.';



		 } else {
			// the post exists, let's update and process the post

			// check if we have a publish button click
			if ( isset ( $_POST['makeit'] ) ) { // final processing

				if ( trucollector_option( 'notify' ) != '') {
				// Let's do some EMAIL!

					// who gets mail? They do.
					$to_recipients = explode( "," ,  trucollector_option( 'notify' ) );

					$subject = 'New ' . get_trucollector_collection_single_item() . ' submitted to ' . get_bloginfo();

					if ( $wNotes ) $message .= '<br /><br />There are some extra notes from the author:<blockquote>' . $wNotes . '</blockquote>';

					// turn on HTML mail
					add_filter( 'wp_mail_content_type', 'set_html_content_type' );

					// mail it!
					wp_mail( $to_recipients, $subject, $message);

					// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
					remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

				} else {
					// updated but still in draft mode

					// if user provided email address, send instructions to use link to edit if not done before
					if ( isset( $wEmail ) and !$linkEmailed  ) trucollector_mail_edit_link( $post_id, 'draft' );

				}  // isset( $_POST['makeit']

				// add the id to our array of post information so we can issue an update
				$w_information['ID'] = $post_id;

		 		// update the post
				wp_update_post( $w_information );

				// store the author as post meta data
				update_post_meta($post_id, 'shared_by', $wAuthor);

				// store the email as post meta data
				update_post_meta($post_id, 'wEmail', $wEmail);

				// store the source of the image (text or URL)
				if ( trucollector_option('use_source') > 0 ) {
					update_post_meta($post_id, 'source', $wSource);
				}

				// store the license code
				if ( trucollector_option('use_license') > 0 ) {
					update_post_meta($post_id, 'license', $wLicense);
				}

				// store notes for editor
				if ( $wNotes ) update_post_meta($post_id, 'editor_notes', $wNotes);


				// track the comment notification preference
				if ( trucollector_option( 'allow_comments' )  ) update_post_meta($post_id, 'wCommentNotify',  $wCommentNotify);

				// update the tags
				wp_set_post_tags( $post_id, $wTags);

				// set featured image
				set_post_thumbnail( $post_id, $wFeatureImageID);

				// update featured image alt
				update_post_meta($wFeatureImageID, '_wp_attachment_image_alt', $wAlt);


			} // isset( $_POST['makeit']
		} // post_id = 0

	} // count errors

} elseif ( $wAccessCodeOk ) {
	// first time entry
	// default welcome message

	$box_style = '<div class="notify"><span class="symbol icon-info"></span> ';

	// ------------------------ re-edit check ------------------------

	// check for query vars that indicate this is a edit request
	$tk  = get_query_var( 'tk', 0 );    // magic token to check

	// is re-edit attempt
	if ( ( $tk )  ) {

		$is_re_edit = true;

		$wid = trucollector_get_id_from_tk( $tk );

		if ( $wid ) {
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

			// get image alt tag
			$wAlt = get_post_meta($wFeatureImageID, '_wp_attachment_image_alt', true);

			// source
			$wSource = get_post_meta( $wid, 'source', 1 );

			// notes
			$wNotes = get_post_meta( $wid, 'editor_notes', 1 );

			// license
			$wLicense = get_post_meta( $wid, 'license', 1 );

			// comment notification preference
			$wCommentNotify = get_post_meta( $wid, 'wCommentNotify', 1 );

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
			$feedback_msg = 'This URL does not match the edit key. Please check the link from your email again, or return to your published ' . get_trucollector_collection_single_item() . ' and click the button at the bottom to send an edit link.';
			$is_published = true;  // not really but it serves to hide the form.
			$box_style = '<div class="notify notify-red"><span class="symbol icon-error"></span> ';
		} // end is wid
	} // end is tk

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

		    		<?php echo $box_style . $feedback_msg . '</div>';?>

				<?php if (!$wAccessCodeOk) : // show the access code form ?>

					<form  id="splotboxform" method="post" action="">
						<fieldset>
							<label for="wAccess">Access Code</label><br />
							<p>Enter the special code to access the sharing form</p>
							<input type="text" name="wAccess" id="wAccess" class="required" value="<?php echo $wAccess?>"  />
						</fieldset>

						<fieldset>
						<?php wp_nonce_field( 'trucollector_form_access', 'trucollector_form_access_submitted' )?>

						<input type="submit" class="pretty-button pretty-button-final" value="Check Code" id="checkit" name="checkit">
						</fieldset>
					</form>

			<?php elseif ( !$is_published ) : // show form it has not been published ?>


				<form  id="collectorform" class="collectorform" method="post" action="" enctype="multipart/form-data">

					<fieldset id="theTitle">
					<label for="wTitle"><?php trucollector_form_item_title() ?> (required)</label><br />
					<p><?php trucollector_form_item_title_prompt() ?> </p>
					<input type="text" name="wTitle" id="wTitle" class="required" value="<?php echo $wTitle; ?>" tabindex="1" />
				</fieldset>



				<fieldset id="theHeaderImage">
					<label for="headerImage"><?php trucollector_form_item_upload() ?> (required)</label>

					<div class="uploader">
						<input id="wFeatureImage" name="wFeatureImage" type="hidden" value="<?php echo $wFeatureImageID?>" />

						<?php

						if ($wFeatureImageID) {
							// header image identified
							$defthumb = wp_get_attachment_image_src( $wFeatureImageID, 'thumbnail' );
						} else {
							// header image optional, use placeholder
							$defthumb = [];
							$defthumb[] = 'https://place-hold.it/150x150?text=Upload+Image';

						}

						echo '<img src="' . $defthumb[0] . '" alt="Upload Image" id="headerthumb" />';
						?>

						<input id="wDefThumbURL" name="wDefThumbURL" type="hidden" value="<?php echo $defthumb[0]?>" />
						</div>

						<p><?php trucollector_form_item_upload_prompt() ?> <span id="uploadresponse"><?php echo $w_thumb_status?></span><br clear="left"></p>

						<div id="splotdropzone">
							<input type="file" accept="image/*" name="wUploadImage" id="wUploadImage">
							<p id="dropmessage">Drag file or click to select file to upload</p>
						</div>
					</fieldset>

				<fieldset id="theAlt">
					<?php
  						$required = (trucollector_option('img_alt') == 1) ? '(required)' : '(highly reccomennded)';
  				   ?>

					<label for="wAlt"><?php trucollector_form_item_img_alt()?> <?php echo $required?></label><br />
					<p><?php trucollector_form_item_img_alt_prompt()?></p>
					<input type="text" name="wAlt" id="wAlt"  value="<?php echo $wAlt; ?>" tabindex="3" />
				</fieldset>

				<fieldset id="theAuthor">
					<label for="wAuthor"><?php trucollector_form_item_author()?></label><br />
					<p><?php trucollector_form_item_author_prompt()?></p>
					<input type="text" name="wAuthor" id="wAuthor" class="required" value="<?php echo $wAuthor; ?>" tabindex="4" />
				</fieldset>


  				<?php if (  trucollector_option('use_caption') > '0'):
  					$required = (trucollector_option('use_caption') == 2) ? '(required)' : '';
  				?>

					<fieldset id="theText">
							<label for="wText"><?php trucollector_form_item_description() ?> <?php echo $required?> </label>
							<p><?php trucollector_form_item_description_prompt()?> </p>

							<?php if (  trucollector_option('caption_field') == 's'):?>

							<input id="wRichText" type="hidden" value="0">

							<textarea name="wText" id="wText" rows="4"  tabindex="5"><?php echo stripslashes( $wText );?></textarea><p style="font-size:0.8rem">To create hyperlinks use this shortcode<br /><code>[link url="http://www.themostamazingwebsiteontheinternet.com/" text="the coolest site on the internet"]</code><br />If you omit <code>text=</code> the URL will be the link text.</p>

							<?php else:?>


							<input id="wRichText" type="hidden" value="1">

							<?php
							// set up for inserting the WP post editor
							$settings = array( 'textarea_name' => 'wText', 'editor_height' => '300',  'tabindex'  => "6", 'media_buttons' => true, 'drag_drop_upload' => true);

							wp_editor(  stripslashes( $wText ), 'wTextHTML', $settings );

							?>


							<?php endif?>

					</fieldset>

				<?php endif?>


  				<?php if (  trucollector_option('use_source') > '0'):
  					$required = (trucollector_option('use_source') == 2) ? '(required)' : '';
  				?>

					<fieldset id="theSource">
						<label for="wSource"><?php trucollector_form_item_image_source() ?> <?php echo $required?></label>
						<p><?php trucollector_form_item_image_source_prompt() ?></p>
						<input type="text" name="wSource" id="wSource" class="required" value="<?php echo $wSource; ?>" tabindex="7" />
					</fieldset>

				<?php endif?>

  				<?php if (  trucollector_option('use_license') > '0'):
  					$required = (trucollector_option('use_license') == 2) ? '(required)' : '';
  				?>


					<fieldset  id="theLicense">
						<label for="wLicense"><?php trucollector_form_item_license() ?> <?php echo $required?></label>
						<p><?php trucollector_form_item_license_prompt() ?></p>

						<select name="wLicense" id="wLicense" tabindex="8" />
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

							echo '<label><input type="checkbox" name="wCats[]" tabindex="9" value="' . $acat->term_id . '"' . $checked . '> ' . $acat->name . '</label><br />';
						}

						?>

					</fieldset>

				<?php endif?>


				<?php if (trucollector_option('show_tags') ):?>
					<fieldset  id="theTags">
						<label for="wTags"><?php  trucollector_form_item_tags() ?></label>
						<p><?php  trucollector_form_item_tags_prompt() ?></p>

						<input type="text" name="wTags" id="wTags" value="<?php echo $wTags; ?>" tabindex="10"  />
					</fieldset>
				<?php endif?>



				<?php if (trucollector_option('show_email') ):?>
					<fieldset id="theEmail">
						<label for="wEmail"><?php trucollector_form_item_email() ?> (<?php echo ( trucollector_option('show_email') == '2' ) ? 'required' : 'optional'?>)</label><br />
						<p><?php trucollector_form_item_email_prompt() ?>
						<?php
							if  ( !empty( trucollector_option('email_domains') ) ) {
								echo ' Allowable email addresses must be ones from domains <code>' . trucollector_option('email_domains') . '</code>.';
							}
						?>

						</p>
						<input type="text" name="wEmail" id="wEmail" name="wEmail"  value="<?php echo $wEmail; ?>" />



					<?php if (trucollector_option('allow_comments') ):?>

							<label for="wCommentNotify" style="display:none;">Comment Notification</label>

							<?php $checked = ( $wCommentNotify ) ? ' checked="checked"' : '';?>
							<input type="checkbox" name="wCommentNotify" value="1"<?php echo $checked?>>  Send  notifications of comments to this address
					<?php endif?>
					</fieldset>
				<?php endif?>


				<?php if (trucollector_option('show_notes') ):?>
					<fieldset  id="theNotes">
							<label for="wNotes"><?php trucollector_form_item_editor_notes() ?></label>
							<p><?php trucollector_form_item_editor_notes_prompt() ?></p>

							<textarea name="wNotes" id="wNotes" rows="10"  tabindex="11"><?php echo stripslashes( $wNotes );?></textarea>
					</fieldset>
				<?php endif?>

				<fieldset id="theButtons">
					<label for="theButtons"><?php trucollector_form_item_submit_buttons() ?></label>
					<?php  wp_nonce_field( 'trucollector_form_make', 'trucollector_form_make_submitted' ); ?>
					<p><?php trucollector_form_item_submit_buttons_prompt() ?></p>


					<?php if ( $post_id ) : //draft saved at least once?>

						<?php
						// set up button names

						if ( $is_re_edit ) {
							$save_btn_txt = "Publish Changes";
						} else {
							$save_btn_txt = ( trucollector_option('new_item_status') == 'publish') ? "Share Now" : "Submit for Review";
						}
						?>

						<input type="submit" value="Check Info" id="checkit" name="checkit">
						<a href="<?php echo site_url() . '/?p=' . $post_id . '&preview=true&ispre=1'?>" title="Preview of your item."  id="wPreview" class="fbutton<?php echo $previewBtnState?>" target="_blank">Preview</a>
						<input type="submit" value="<?php echo $save_btn_txt?>" id="makeit" name="makeit" <?php echo $submitBtnState?>>

					<?php else:?>

						<input type="submit" value="Check and Review" id="checkit" name="checkit">


					<?php endif?>

				<input name="post_id" type="hidden" value="<?php echo $post_id?>" />
				<input name="wAccessCodeOk" type="hidden" value="true" />


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
