<?php

# -----------------------------------------------------------------
# Customizer Stuff
# -----------------------------------------------------------------

add_action( 'customize_register', 'trucollector_register_theme_customizer' );


function trucollector_register_theme_customizer( $wp_customize ) {
	// Create custom panel.
	$wp_customize->add_panel( 'customize_collector', array(
		'priority'       => 25,
		'theme_supports' => '',
		'title'          => __( 'TRU Collector', 'fukasawa'),
		'description'    => __( 'Customizer Stuff', 'fukasawa'),
	) );

	// Add section for the general stuff
	$wp_customize->add_section( 'collections' , array(
		'title'    => __('Collection Info','fukasawa'),
		'panel'    => 'customize_collector',
		'priority' => 10
	) );

	// Add section for the collect form
	$wp_customize->add_section( 'collect_form' , array(
		'title'    => __('Collect Form','fukasawa'),
		'panel'    => 'customize_collector',
		'priority' => 12
	) );

	// Add setting for singular item
	$wp_customize->add_setting( 'singular_item', array(
		 'default'  => __( 'item', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for title label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_singular',
		    array(
		        'label'    => __( 'Singular Item', 'fukasawa'),
		        'priority' => 11,
		        'description' => __( 'The name for one thing in this collection' ),
		        'section'  => 'collections',
		        'settings' => 'singular_item',
		        'type'     => 'text'
		    )
	    )
	);

	// Add setting for singular item
	$wp_customize->add_setting( 'plural_item', array(
		 'default'  => __( 'items', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control fortitle label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'plural_item',
		    array(
		        'label'    => __( 'Plural Items', 'fukasawa'),
		        'priority' => 11,
		        'description' => __( 'The name for more than one thing in this collection' ),
		        'section'  => 'collections',
		        'settings' => 'plural_item',
		        'type'     => 'text'
		    )
	    )
	);



	// Add setting for default prompt
	$wp_customize->add_setting( 'default_prompt', array(
		 'default'           => __( 'Add something to this collection? Yes! Use the form below to share it', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );


	// Add setting for singular item
	$wp_customize->add_setting( 'default_prompt', array(
		 'default'           => __( '', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );


	// Add control for default prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'default_prompt',
		    array(
		        'label'    => __( 'Default Prompt', 'fukasawa'),
		        'priority' => 10,
		        'description' => __( 'The opening message above the form.' ),
		        'section'  => 'collect_form',
		        'settings' => 'default_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for title label
	$wp_customize->add_setting( 'item_title', array(
		 'default'           => __( 'Title for this Item', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for title label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_title',
		    array(
		        'label'    => __( 'Title Label', 'fukasawa'),
		        'priority' => 11,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_title',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for title description
	$wp_customize->add_setting( 'item_title_prompt', array(
		 'default'           => __( 'Enter a descriptive title that works well as a headline when listed in this site.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for title description
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_title_prompt',
		    array(
		        'label'    => __( 'Title Prompt', 'fukasawa'),
		        'priority' => 12,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_title_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for image upload label
	$wp_customize->add_setting( 'item_upload', array(
		 'default'           => __( 'Upload an Image for this Item', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for image upload  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_upload',
		    array(
		        'label'    => __( 'Image Upload Label', 'fukasawa'),
		        'priority' => 13,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_upload',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for image upload prompt
	$wp_customize->add_setting( 'item_upload_prompt', array(
		 'default'           => __( 'Upload an image by dragging its icon to the window that opens when clicking  "Select Image" button. Larger JPG, PNG images are best. To preserve animation, GIFs should be no larger than 500px wide.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for image upload prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_upload_prompt',
		    array(
		        'label'    => __( 'Image Upload Prompt', 'fukasawa'),
		        'priority' => 14,
		        'description' => __( 'Directions for image uploads' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_upload_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for author  label
	$wp_customize->add_setting( 'item_author', array(
		 'default'           => __( 'Who is Uploading the Item?', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for author  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_author',
		    array(
		        'label'    => __( 'Credit Label', 'fukasawa'),
		        'priority' => 15,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_author',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for author  label prompt
	$wp_customize->add_setting( 'item_author_prompt', array(
		 'default'           => __( 'Take credit for sharing this item by entering your name(s),  twitter handle(s), or remain "Anonymous".', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for author  label prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_author_prompt',
		    array(
		        'label'    => __( 'Image Upload Prompt', 'fukasawa'),
		        'priority' => 16,
		        'description' => __( 'Directions for the author/uploader credit' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_author_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for description  label
	$wp_customize->add_setting( 'item_description', array(
		 'default'           => __( 'Item Description', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for description  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_description',
		    array(
		        'label'    => __( 'Description Label', 'fukasawa'),
		        'priority' => 20,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_description',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for description  label prompt
	$wp_customize->add_setting( 'item_description_prompt', array(
		 'default'           => __( 'Enter descriptive content to include with the item.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for description  label prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_description_prompt',
		    array(
		        'label'    => __( 'Item Description Prompt', 'fukasawa'),
		        'priority' => 22,
		        'description' => __( 'Directions for the description entry field' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_description_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for image source  label
	$wp_customize->add_setting( 'item_image_source', array(
		 'default'           => __( 'Source of Image', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for image source  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_image_source',
		    array(
		        'label'    => __( 'Image Source Label', 'fukasawa'),
		        'priority' => 24,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_image_source',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for image source  prompt
	$wp_customize->add_setting( 'item_image_source_prompt', array(
		 'default'           => __( 'Enter name of a person, web site, etc to give credit for the image submitted above.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for image source prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_image_source_prompt',
		    array(
		        'label'    => __( 'Image Source Prompt', 'fukasawa'),
		        'priority' => 26,
		        'description' => __( 'Directions for the image source field' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_image_source_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for license  label
	$wp_customize->add_setting( 'item_license', array(
		 'default'           => __( 'License for Reuse', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for license  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_license',
		    array(
		        'label'    => __( 'License Label', 'fukasawa'),
		        'priority' => 27,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_license',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for license  prompt
	$wp_customize->add_setting( 'item_license_prompt', array(
		 'default'           => __( 'Indicate a reuse license associated with the image. If this is your own image,  select a license to share it under.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for license prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_license_prompt',
		    array(
		        'label'    => __( 'Image License Prompt', 'fukasawa'),
		        'priority' => 28,
		        'description' => __( 'Directions for the license selection' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_license_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for categories  label
	$wp_customize->add_setting( 'item_categories', array(
		 'default'           => __( 'Categories', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for categories  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_categories',
		    array(
		        'label'    => __( 'Categories Label', 'fukasawa'),
		        'priority' => 30,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_categories',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for categories  prompt
	$wp_customize->add_setting( 'item_categories_prompt', array(
		 'default'           => __( 'Check all categories that will help organize this item.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for categories prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_categories_prompt',
		    array(
		        'label'    => __( 'Categories Prompt', 'fukasawa'),
		        'priority' => 32,
		        'description' => __( 'Directions for the categories selection' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_categories_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for tags  label
	$wp_customize->add_setting( 'item_tags', array(
		 'default'           => __( 'Tags', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for tags  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_tags',
		    array(
		        'label'    => __( 'Tags Label', 'fukasawa'),
		        'priority' => 33,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_tags',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for tags  prompt
	$wp_customize->add_setting( 'item_tags_prompt', array(
		 'default'           => __( 'Add any descriptive tags for this item. Separate multiple ones with commas.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for tags prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_tags_prompt',
		    array(
		        'label'    => __( 'Tags Prompt', 'fukasawa'),
		        'priority' => 34,
		        'description' => __( 'Directions for  tags entry' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_tags_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);


	// setting for email address  label
	$wp_customize->add_setting( 'item_email', array(
		 'default'           => __( 'Your Email Address', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for email address  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_email',
		    array(
		        'label'    => __( 'Email Address Label', 'fukasawa'),
		        'priority' => 36,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_email',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for email address  prompt
	$wp_customize->add_setting( 'item_email_prompt', array(
		 'default'           => __( 'If you provide an email address when your writing is published, you can request a special link that will allow you to edit it again in the future.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for email address prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_email_prompt',
		    array(
		        'label'    => __( 'Email Address Prompt', 'fukasawa'),
		        'priority' => 38,
		        'description' => __( 'Directions for email address entry' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_email_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);


	// setting for editor notes  label
	$wp_customize->add_setting( 'item_editor_notes', array(
		 'default'           => __( 'Notes to the Editor', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for editor notes  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_editor_notes',
		    array(
		        'label'    => __( 'Editor Notes Label', 'fukasawa'),
		        'priority' => 39,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_editor_notes',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for editor notes  prompt
	$wp_customize->add_setting( 'item_editor_notes_prompt', array(
		 'default'           => __( 'Add any notes or messages to send to the site manager; this will not be part of what is published. If you wish to be contacted, leave an email address or twitter handle.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for editor notes prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_editor_notes_prompt',
		    array(
		        'label'    => __( 'Editor Notes Prompt', 'fukasawa'),
		        'priority' => 40,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_editor_notes_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);


	// setting for submit buttons label
	$wp_customize->add_setting( 'item_submit_buttons', array(
		 'default'           => __( 'Share It', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for editor notes  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_submit_buttons',
		    array(
		        'label'    => __( 'Preview and Share Buttons', 'fukasawa'),
		        'priority' => 42,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_submit_buttons',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for submit buttons prompt
	$wp_customize->add_setting( 'item_submit_buttons_prompt', array(
		 'default'           => __( 'You can preview how your item will look when published; when ready, share it to this collection.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Control for editor notes prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_submit_buttons_prompt',
		    array(
		        'label'    => __( 'Preview and Share Buttons Prompt', 'fukasawa'),
		        'priority' => 50,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_submit_buttons_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// Add setting for re-edit prompt
	$wp_customize->add_setting( 're_edit_prompt', array(
		 'default'           => __( 'You can now edit this previously saved item, save the changes, then publish the changes.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );

	// Add control for re-edit prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		're_edit_prompt',
		    array(
		        'label'    => __( 'Return Edit Prompt', 'fukasawa'),
		        'priority' => 52,
		        'description' => __( 'The opening message greeting above the form for a request to edit a previously published item.' ),
		        'section'  => 'collect_form',
		        'settings' => 're_edit_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);



 	// Sanitize text
	function sanitize_text( $text ) {
	    return sanitize_text_field( $text );
	}
}

function trucollector_collection_single_item() {
	 if ( get_theme_mod( 'singular_item') != "" ) {
	 	echo get_theme_mod( 'singular_item');
	 }	else {
	 	echo 'item';
	 }
}


function get_trucollector_collection_single_item() {
	 if ( get_theme_mod( 'singular_item') != "" ) {
	 	return get_theme_mod( 'singular_item');
	 }	else {
	 	return 'item';
	 }
}


function trucollector_collection_plural_item() {
	 if ( get_theme_mod( 'plural_item') != "" ) {
	 	echo get_theme_mod( 'plural_item');
	 }	else {
	 	echo 'items';
	 }
}

function get_trucollector_collection_plural_item() {
	 if ( get_theme_mod( 'plural_item') != "" ) {
	 	return ( get_theme_mod( 'plural_item'));
	 }	else {
	 	return  ('items');
	 }
}


function trucollector_form_default_prompt() {
	 if ( get_theme_mod( 'default_prompt') != "" ) {
	 	return get_theme_mod( 'default_prompt');
	 }	else {
	 	return 'Add something to this collection? Yes! Use the form below to share it';
	 }
}

function trucollector_form_item_title() {
	 if ( get_theme_mod( 'item_title') != "" ) {
	 	echo get_theme_mod( 'item_title');
	 }	else {
	 	echo 'Title for this Item';
	 }
}

function trucollector_form_item_title_prompt() {
	 if ( get_theme_mod( 'item_title_prompt') != "" ) {
	 	echo get_theme_mod( 'item_title_prompt');
	 }	else {
	 	echo 'Enter a descriptive title that works well as a headline when listed in this site.';
	 }
}

function trucollector_form_item_upload() {
	 if ( get_theme_mod( 'item_upload') != "" ) {
	 	echo get_theme_mod( 'item_upload');
	 }	else {
	 	echo 'Upload an Image for this Item';
	 }
}

function trucollector_form_item_upload_prompt() {
	 if ( get_theme_mod( 'item_upload_prompt') != "" ) {
	 	echo get_theme_mod( 'item_upload_prompt');
	 }	else {
	 	echo 'Upload an image by dragging its icon to the window that opens when clicking  "Select Image" button. Larger JPG, PNG images are best. To preserve animation, GIFs should be no larger than 500px wide.';
	 }
}

function trucollector_form_item_author() {
	 if ( get_theme_mod( 'item_author') != "" ) {
	 	echo get_theme_mod( 'item_author');
	 }	else {
	 	echo 'Who is Uploading the Item?';
	 }
}

function trucollector_form_item_author_prompt() {
	 if ( get_theme_mod( 'item_author_prompt') != "" ) {
	 	echo get_theme_mod( 'item_author_prompt');
	 }	else {
	 	echo 'Take credit for sharing this item by entering your name(s),  twitter handle(s), or remain "Anonymous".';
	 }
}

function trucollector_form_item_description() {
	 if ( get_theme_mod( 'item_description') != "" ) {
	 	echo get_theme_mod( 'item_description');
	 }	else {
	 	echo 'Description Label';
	 }
}

function trucollector_form_item_description_prompt() {
	 if ( get_theme_mod( 'item_description_prompt') != "" ) {
	 	echo get_theme_mod( 'item_description_prompt');
	 }	else {
	 	echo 'Enter a descriptive caption to include with the item.';
	 }
}

function trucollector_form_item_image_source() {
	 if ( get_theme_mod( 'item_image_source') != "" ) {
	 	echo get_theme_mod( 'item_image_source');
	 }	else {
	 	echo 'Source of Image';
	 }
}

function trucollector_form_item_image_source_prompt() {
	 if ( get_theme_mod( 'item_image_source_prompt') != "" ) {
	 	echo get_theme_mod( 'item_image_source_prompt');
	 }	else {
	 	echo 'Enter name of a person, web site, etc to give credit for the image submitted above.';
	 }
}

function trucollector_form_item_license() {
	 if ( get_theme_mod( 'item_license') != "" ) {
	 	echo get_theme_mod( 'item_license');
	 }	else {
	 	echo 'Item License';
	 }
}

function trucollector_form_item_license_prompt() {
	 if ( get_theme_mod( 'item_license_prompt') != "" ) {
	 	echo get_theme_mod( 'item_license_prompt');
	 }	else {
	 	echo 'Select the appropriate reuse license for this item.';
	 }
}

function trucollector_form_item_categories() {
	 if ( get_theme_mod( 'item_categories') != "" ) {
	 	echo get_theme_mod( 'item_categories');
	 }	else {
	 	echo 'Categories';
	 }
}

function trucollector_form_item_categories_prompt() {
	 if ( get_theme_mod( 'item_categories_prompt') != "" ) {
	 	echo get_theme_mod( 'item_categories_prompt');
	 }	else {
	 	echo 'Check all categories that will help organize this item.';
	 }
}

function trucollector_form_item_tags() {
	 if ( get_theme_mod( 'item_tags') != "" ) {
	 	echo get_theme_mod( 'item_tags');
	 }	else {
	 	echo 'Tags';
	 }
}

function trucollector_form_item_tags_prompt() {
	 if ( get_theme_mod( 'item_tags_prompt') != "" ) {
	 	echo get_theme_mod( 'item_tags_prompt');
	 }	else {
	 	echo 'Add any descriptive tags for this item. Separate multiple ones with commas.';
	 }
}


function trucollector_form_item_email() {
	 if ( get_theme_mod( 'item_email') != "" ) {
	 	echo get_theme_mod( 'item_email');
	 }	else {
	 	echo 'Your Email Address';
	 }
}

function trucollector_form_item_email_prompt() {
	 if ( get_theme_mod( 'item_email_prompt') != "" ) {
	 	echo get_theme_mod( 'item_email_prompt');
	 }	else {
	 	echo 'If you provide an email address when your writing is published, you can request a special link that will allow you to edit it again in the future.';
	 }
}


function trucollector_form_item_editor_notes() {
	 if ( get_theme_mod( 'item_editor_notes') != "" ) {
	 	echo get_theme_mod( 'item_editor_notes');
	 }	else {
	 	echo 'Notes to the Editor';
	 }
}

function trucollector_form_item_editor_notes_prompt() {
	 if ( get_theme_mod( 'item_editor_notes_prompt') != "" ) {
	 	echo get_theme_mod( 'item_editor_notes_prompt');
	 }	else {
	 	echo 'Add any notes or messages to send to the site manager; this will not be part of what is published. If you wish to be contacted, leave an email address or twitter handle.';
	 }
}


function trucollector_form_item_submit_buttons() {
	 if ( get_theme_mod( 'item_submit_buttons') != "" ) {
	 	echo get_theme_mod( 'item_submit_buttons');
	 }	else {
	 	echo 'Share It';
	 }
}

function trucollector_form_item_submit_buttons_prompt() {
	 if ( get_theme_mod( 'item_submit_buttons_prompt') != "" ) {
	 	echo get_theme_mod( 'item_submit_buttons_prompt');
	 }	else {
	 	echo 'You can preview how your item will look when published; when ready, share it to this collection.';
	 }
}

function trucollector_form_re_edit_prompt() {
	 if ( get_theme_mod( 're_edit_prompt') != "" ) {
	 	return get_theme_mod( 're_edit_prompt');
	 }	else {
	 	return 'You can now edit this previously saved item, save the changes, then publish the changes.';
	 }
}

?>
