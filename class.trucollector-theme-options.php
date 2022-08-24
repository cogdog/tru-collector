<?php
// manages all of the theme options
// heavy lifting via http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
// Revision Sept 16, 2016 as jQuery update killed TAB UI


class trucollector_Theme_Options {

	/* Array of sections for the theme options page */
	private $sections;
	private $checkboxes;
	private $settings;

	/* Initialize */
	function __construct() {

		// This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();
		$this->settings = array();
		$this->get_settings();

		$this->sections['general'] = __( 'General Settings' );

		// create a colllection of callbacks for each section heading
		foreach ( $this->sections as $slug => $title ) {
			$this->section_callbacks[$slug] = 'display_' . $slug;
		}

		// enqueue scripts for media uploader
        add_action( 'admin_enqueue_scripts', 'trucollector_enqueue_options_scripts' );

		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );

		if ( ! get_option( 'trucollector_options' ) )
			$this->initialize_settings();
	}

	/* Add page(s) to the admin menu */
	public function add_pages() {
		$admin_page = add_theme_page( 'TRU Collector Options', 'TRU Collector Options', 'manage_options', 'trucollector-options', array( &$this, 'display_page' ) );

		// documents page, but don't add to menu
		$docs_page = add_theme_page( 'TRU Collector Documentation', '', 'manage_options', 'trucollector-docs', array( &$this, 'display_docs' ) );

	}

	/* HTML to display the theme options page */
	public function display_page() {
		echo '<div class="wrap">
		<h1>TRU Collector Options</h1>';

		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
			echo '<div class="updated fade"><p>' . __( 'Theme options updated.' ) . '</p></div>';

		echo '<form action="options.php" method="post" enctype="multipart/form-data">';

		settings_fields( 'trucollector_options' );

		echo  '<h2 class="nav-tab-wrapper"><a class="nav-tab nav-tab-active" href="?page=trucollector-options">Settings</a>
	<a class="nav-tab" href="?page=trucollector-docs">Documentation</a></h2>';

		do_settings_sections( $_GET['page'] );


		echo '<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" /></p>
		</form>
		</div>

		<script type="text/javascript">
		jQuery(document).ready(function($) {

			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});

			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});

			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
		});
		</script>';

	}

	/*  display documentation in a tab */
	public function display_docs() {
		// This displays on the "Documentation" using docsify-this to render directly from repo

	 	echo '<div class="wrap">
		<h1>TRU Collector Documentation</h1>
		<h2 class="nav-tab-wrapper">
		<a class="nav-tab" href="?page=trucollector-options">Settings</a>
		<a class="nav-tab nav-tab-active" href="?page=trucollector-docs">Documentation</a></h2>
		<p>The most current TRU Collector documentation is displayed below (<a href="https://docsify-this.net/?basePath=https://raw.githubusercontent.com/cogdog/tru-collector/master&homepage=docs.md&sidebar=true#/">view in a new window</a>). Generated with <a href="https://docsify-this.net/" target="_blank">Docsify This</a>.</p>';

		echo '<div class="iframe-container">
		
		
		<iframe src="https://docsify-this.net/?basePath=https://raw.githubusercontent.com/cogdog/tru-collector/master&homepage=docs.md#/" title="TRU Collector Documentation" allowfullscreen></iframe>
		</div>
	</div>';


	}



	/* Define all settings and their defaults */
	public function get_settings() {

		// for file upload checks
		$max_upload_size = round(wp_max_upload_size() / 1000000);

		/* General Settings
		===========================================*/


		// ------- access options
		$this->settings['access_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Access to Collector',
			'std'    => 'Set code to access collection form',
			'type'    => 'heading'
		);


		$this->settings['accesscode'] = array(
			'title'   => __( 'Access Code' ),
			'desc'    => __( 'Set necessary code to access the collector tool; leave blank to make wide open' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);


		$this->settings['accesshint'] = array(
			'title'   => __( 'Access Hint' ),
			'desc'    => __( 'Provide a suggestion if someone cannot guess the code. Not super secure, but hey.' ),
			'std'     => 'Name of this site (lower the case, Ace!)',
			'type'    => 'text',
			'section' => 'general'
		);

		$this->settings['pages_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Special Pages Setup',
			'std'    => 'Choose the Pages used for special Collector functions',
			'type'    => 'heading'
		);

		// get all pages on site with template for the Writing Form
		$found_pages = get_pages_with_template('page-collect.php');

		// the function returns an array of id => page title, first item is the menu selection item
		$page_desc = 'Set the Page that should be used for the sharing form.';
		if ( count( $found_pages ) > 1 ) {
			$page_desc = 'Set the Page that should be used for the sharing form.';
			$page_std =  array_keys( $found_pages)[1];

		} else {
			$trypage = get_page_by_path('collect');

			if ( $trypage ) {
				$page_std = $trypage->ID;
				$found_pages = array( 0 => 'Select Page', $page_std => $trypage->post_title );
			} else {
						$page_desc = 'No pages have been created with the Add to Collection template. This is required to enable access to the collector form. <a href="' . admin_url( 'post-new.php?post_type=page') . '">Create a new Page</a> and under <strong>Page Attributes</strong> select <code>Add to Collection</code> for the Template.';
						$page_std = '';
			}
		}

		$this->settings['collect_page'] = array(
			'section' => 'general',
			'title'   => __( 'Page for the sharing form (Add to Collection)'),
			'desc'    => $page_desc,
			'type'    => 'select',
			'std'     =>  $page_std,
			'choices' => $found_pages
		);

		// get all pages on site with template for the Licenses
		$found_pages = get_pages_with_template('page-licensed.php');

		// the function returns an array of id => page title, first item is the menu selection item
		if ( count( $found_pages ) > 1 ) {
			$page_desc = 'Set the Page that should be used for displaying content by licenses.';
			$page_std =  array_keys( $found_pages)[1];

		} else {
			$trypage = get_page_by_path('licensed');

			if ( $trypage ) {
				$page_std = $trypage->ID;
				$found_pages = array( 0 => 'Select Page', $page_std => $trypage->post_title );

			} else {
				$page_desc = 'No pages have been created with the Items by License template. This is used to display content by reuse license applied. <a href="' . admin_url( 'post-new.php?post_type=page') . '">Create a new Page</a> and under <strong>Page Attributes</strong> select <code>Items by License</code> for the Template.';
				$page_std = '';
			}
		}

		$this->settings['license_page'] = array(
			'section' => 'general',
			'title'   => __( 'Page for the Show By Licenses page (Items by License)'),
			'desc'    => $page_desc,
			'type'    => 'select',
			'std'     =>  $page_std,
			'choices' => $found_pages
		);


		$this->settings['publish_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Publish Settings',
			'std'    => '',
			'type'    => 'heading'
		);


		$this->settings['new_item_status'] = array(
			'section' => 'general',
			'title'   => __( 'Status For New Items' ),
			'desc'    => __( 'Set to draft to moderate submissions' ),
			'type'    => 'radio',
			'std'     => 'publish',
			'choices' => array(
				'publish' => 'Publish immediately',
				'pending' => 'Set to draft',
			)
		);

		// ------- sort options
		$this->settings['sort_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Item Sorting',
			'std'    => 'Set the order of items on home page and archives.',
			'type'    => 'heading'
		);


		$this->settings['sort_by'] = array(
			'section' => 'general',
			'title'   => __( 'Sort Items by'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'date',
			'choices' => array (
							'date' => 'Date Published (default)',
							'title' => 'Title',
					)
		);

		$this->settings['sort_direction'] = array(
			'section' => 'general',
			'title'   => __( 'Sort Order'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'DESC',
			'choices' => array (
							'DESC' => 'Descending  (default)',
							'ASC' => 'Ascending',
					)
		);

		$this->settings['sort_applies'] = array(
			'section' => 'general',
			'title'   => __( 'Sort Applied To'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'all',
			'choices' => array (
							'all' => 'All Items',
							'front' => 'Front Page Only',
							'cat' => 'Categories Only',
							'tag' => 'Tags Only',
							'tagcat' => 'Categories and Tags'
					)
		);

		// ------- single item
		$this->settings['single_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Fields and Options for Items',
			'std'    => 'Set which field are used on collection form and single item display.',
			'type'    => 'heading'
		);

		$this->settings['upload_max'] = array(
			'title'   => __( 'Maximum Upload File Size' ),
			'desc'    => __( 'Set limit for file uploads in Mb (maximum possible for this site is ' . $max_upload_size . ' Mb).' ),
			'std'     => $max_upload_size,
			'type'    => 'text',
			'section' => 'general'
		);

		$this->settings['use_caption'] = array(
			'section' => 'general',
			'title'   => __( 'Use description field on submission form and item display?'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array (
							'0' => 'No',
							'1' => 'Yes, but make it optional',
							'2' => 'Yes, and make it required'
					)
		);

		$this->settings['caption_field'] = array(
			'section' => 'general',
			'title'   => __( 'Description Field'),
			'desc'    => __( 'Use plain text entry field as captions or rich text editor for full narrative.'),
			'type'    => 'radio',
			'std'     => 's',
			'choices' => array (
							's' => 'Simple plain text input field (accepts hypertext link shortcode)',
							'r' => 'Rich text editor'
					)
		);

		$this->settings['def_text'] = array(
			'title'   => __( 'Default Description' ),
			'desc'    => __( 'Enter default content that will appear in the collecting form editing field if you want tp provide some example of the type of response you wish to collect (e.g. headers, place holder example descriptions). Leave blank to start with an empty form field.' ),
			'std'     => '',
			'type'    => ( trucollector_option('caption_field') == 'r' ) ? 'richtextarea' : 'textarea',
			'section' => 'general'
		);

		$this->settings['img_alt'] = array(
			'section' => 'general',
			'title'   => __( 'Make alternative descriptions for images required?'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array (
							'0' => 'No, it is optional',
							'1' => 'Yes, make it required'
					)
		);

		$this->settings['show_sharedby'] = array(
			'section' => 'general',
			'title'   => __( 'Display name of person sharing?'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array (
							'0' => 'No',
							'1' => 'Yes'
					)
		);

		$this->settings['use_source'] = array(
			'section' => 'general',
			'title'   => __( 'Use source field (e.g. to provide credit for images) on submission form and item display?'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array (
							'0' => 'No',
							'1' => 'Yes, but make it optional',
							'2' => 'Yes, and make it required'
					)
		);

		$this->settings['show_link'] = array(
			'section' => 'general',
			'title'   => __( 'Show URL for media item?'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array (
							'0' => 'No',
							'1' => 'Yes'
					)
		);

		$this->settings['use_license'] = array(
			'section' => 'general',
			'title'   => __( 'Use rights license on submission form and item display?'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array (
							'0' => 'No',
							'1' => 'Yes, but make it optional',
							'2' => 'Yes, and make it required'
					)
		);

		$this->settings['show_attribution'] = array(
			'section' => 'general',
			'title'   => __( 'Cut and Paste Attribution' ),
			'desc'    => __( 'If license options used, show cut and paste attribution on single item displays?' ),
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array(
				'0' => 'No',
				'1' => 'Yes',
			)
		);


 		$this->settings['allow_comments'] = array(
			'section' => 'general',
			'title'   => __( 'Allow Comments?' ),
			'desc'    => __( 'Enable comments on items' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);


  		// Build array to hold options for select, an array of post categories


		$this->settings['show_cats'] = array(
			'section' => 'general',
			'title'   => __( 'Use categories as options for submission or only for admin use'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array (
							'0' => 'No, do not use categories',
							'1' => 'Yes, options on share form and display on single item',
							'2' => 'Yes, but used only by admin to organize (not on collect form)'
					)
		);


		// Walk those cats, store as array index=ID
	  	$all_cats = get_categories('hide_empty=0');
		foreach ( $all_cats as $item ) {
  			$cat_options[$item->term_id] =  $item->name;
  		}

		$this->settings['def_cat'] = array(
			'section' => 'general',
			'title'   => __( 'Default Category for New Items'),
			'desc'    => '',
			'type'    => 'select',
			'std'     => get_option('default_category'),
			'choices' => $cat_options
		);

		$this->settings['show_tags'] = array(
			'section' => 'general',
			'title'   => __( 'Show/use tags?'),
			'desc'    => 'Use tags as options for submission or only for admin use',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array (
							'0' => 'No, do not use tags',
							'1' => 'Yes, options on share form and display on single item',
							'2' => 'Yes, but used only by admin to organize (not on collec form)'

					)
		);

		$this->settings['show_notes'] = array(
			'section' => 'general',
			'title'   => __( 'Show the input field for notes to the editor on the collection form?'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array (
							'0' => 'No',
							'1' => 'Yes'
					)
		);


		$this->settings['email_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Email Settings',
			'std'    => '',
			'type'    => 'heading'
		);

		$this->settings['show_email'] = array(
			'section' => 'general',
			'title'   => __( 'Enable email address field.'),
			'desc'    => ' Setting to <strong>No</strong> will remove this feature from being available on published items and remove option for selecting notification of comments.',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array (
							'0' => 'No',
							'1' => 'Yes, but make it optional',
							'2' => 'Yes, and make it required'
					)
		);

		$this->settings['email_domains'] = array(
			'title'   => __( 'Limit email addresses to domain(s).' ),
			'desc'    => __( 'Seperate multiple domains by commas' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);

		$this->settings['comment_notification'] = array(
			'section' => 'general',
			'title'   => __( 'Show option for comment notification.'),
			'desc'    => ' Setting to <strong>Yes</strong> will provide a check box option for authors to receive notification of comments on their writing (only effective if comments enabled in the <strong>Fields and Options for Items</strong> section).',
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array (
							'0' => 'No',
							'1' => 'Yes'
					)
		);

		$this->settings['admin_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Admin Settings',
			'std'    => '',
			'type'    => 'heading'
		);

		$this->settings['notify'] = array(
			'title'   => __( 'Notification Emails' ),
			'desc'    => __( 'Send notifications to these addresses (separate multiple ones wth commas). They must have an Editor Role on this site. Leave empty to disable notifications.' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);

		/* Reset
		===========================================*/

		$this->settings['reset_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'With Great Reset Power Comes...',
			'std'    => 'Think twice before resetting all options to defaults!',
			'type'    => 'heading'
		);

		$this->settings['reset_theme'] = array(
			'section' => 'general',
			'title'   => __( 'Reset All Options' ),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box and click "Save Changes" below to reset theme options to their defaults.' )
		);


	}

	public function display_general() {
		// section heading for general setttings
		echo '<p>These settings manage the behavior and appearance of your TRU Collector powered site. There are quite a few of them! Check the documentation tab for all the details.</p><p>If this kind of stuff has any value to you, please consider supporting me so I can do more!</p><p style="text-align:center"><a href="https://patreon.com/cogdog" target="_blank"><img src="https://cogdog.github.io/images/badge-patreon.png" alt="donate on patreon"></a> &nbsp; <a href="https://paypal.me/cogdog" target="_blank"><img src="https://cogdog.github.io/images/badge-paypal.png" alt="donate on paypal"></a></p>';
	}


	public function display_reset() {
		// section heading for reset section setttings
	}

	/* HTML output for individual settings */
	public function display_setting( $args = array() ) {

		extract( $args );

		$options = get_option( 'trucollector_options' );

		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;

		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;


		switch ( $type ) {

			case 'heading':
				echo '<tr><td colspan="2" class="alternate"><h3>' . $desc . '</h3><p>' . $std . '</p></td></tr>';

				break;

			case 'checkbox':

				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="trucollector_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';

				break;

			case 'select':
				echo '<select class="select' . $field_class . '" name="trucollector_options[' . $id . ']">';

				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';

				echo '</select>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="trucollector_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				if ( $desc != '' ) echo '<span class="description">' . $desc . '</span><br />';



				break;

			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="trucollector_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . format_for_editor( $options[$id] ) . '</textarea>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				break;


			case 'richtextarea':

				// set up for inserting the WP post editor
				$rich_settings = array( 'textarea_name' => 'trucollector_options[' . $id . ']' , 'editor_height' => '200', 'editor_class' => $field_class );

				$textdefault = (isset( $options[$id] ) ) ? $options[$id] : $std;

				wp_editor(   $textdefault , $id , $rich_settings );

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;


			case 'medialoader':

				echo '<div id="uploader_' . $id . '">';

				if ( $options[$id] )  {
					$front_img = wp_get_attachment_image_src( $options[$id], 'radcliffe' );
					echo '<img id="previewimage_' . $id . '" src="' . $front_img[0] . '" width="640" height="300" alt="default thumbnail" />';
				} else {
					echo '<img id="previewimage_' . $id . '" src="https://placehold.it/640x300" alt="default header image" />';
				}

				echo '<input type="hidden" name="trucollector_options[' . $id . ']" id="' . $id . '" value="' . $options[$id]  . '" />
  <br /><input type="button" class="upload_image_button button-primary" name="_trucollector_button' . $id .'" id="_trucollector_button' . $id .'" data-options_id="' . $id  . '" data-uploader_title="Set Default Header Image" data-uploader_button_text="Select Image" value="Set/Change Image" />
</div><!-- uploader -->';



				if ( $desc != '' ) echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="trucollector_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" /><input type="button" id="showHide" value="Show" />';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'text':
			default:
				echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="trucollector_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';

				if ( $desc != '' ) {

					if ($id == 'def_thumb') $desc .= '<br /><a href="' . $options[$id] . '" target="_blank"><img src="' . $options[$id] . '" style="overflow: hidden;" width="' . $options["index_thumb_w"] . '"></a>';
					echo '<br /><span class="description">' . $desc . '</span>';
				}

				break;
		}
	}

	/* Initialize settings to their default values */
	public function initialize_settings() {

		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}

		update_option( 'trucollector_options', $default_settings );

	}


	/* Register settings via the WP Settings API */
	public function register_settings() {

		register_setting( 'trucollector_options', 'trucollector_options', array ( &$this, 'validate_settings' ) );

		foreach ( $this->sections as $slug => $title ) {
			add_settings_section( $slug, $title, array( &$this, $this->section_callbacks[$slug] ), 'trucollector-options' );
		}

		$this->get_settings();

		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}

	}


	/* tool to create settings fields */
	public function create_setting( $args = array() ) {

		$defaults = array(
			'id'      => 'default_field',
			'title'   => 'Default Field',
			'desc'    => 'This is a default description.',
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'class'   => ''
		);

		extract( wp_parse_args( $args, $defaults ) );

		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);

		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;


		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'trucollector-options', $section, $field_args );

	}


	public function validate_settings( $input ) {

		if ( ! isset( $input['reset_theme'] ) ) {
			$options = get_option( 'trucollector_options' );

			if ( $input['notify'] != $options['notify'] ) {
				$input['notify'] = str_replace(' ', '', $input['notify']);
			}

			// if licenses not used, then show attribution must be false

			if ( $input['use_license'] == '0') {
				$input['show_attribution'] == '0';
			}

			// fix older site options that used 'draft' for save status
			if ($options['new_item_status']  == 'draft') {
				$input['new_item_status'] = 'pending';

			}




			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}

			return $input;
		}

		return false;

	}
 }

$theme_options = new trucollector_Theme_Options();

function trucollector_option( $option ) {
	$options = get_option( 'trucollector_options' );
	if ( isset( $options[$option] ) )
		// fix older site options that used 'draft' for save status
		if ($option == 'new_item_status' and $options['new_item_status'] == 'draft') {
			return 'pending';
		} else {
			return $options[$option];
		}
	else
		return false;
}
?>
