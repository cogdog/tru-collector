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
		// This displays on the "Documentation" tab. 
		
	 	echo '<div class="wrap">
		<h1>TRU Collector Documentation</h1>
		<h2 class="nav-tab-wrapper">
		<a class="nav-tab" href="?page=trucollector-options">Settings</a>
		<a class="nav-tab nav-tab-active" href="?page=trucollector-docs">Documentation</a></h2>';
		
		include( get_stylesheet_directory() . '/includes/trucollector-theme-options-docs.php');
		
		echo '</div>';		
	}



	/* Define all settings and their defaults */
	public function get_settings() {
	
		/* General Settings
		===========================================*/


		// ------- access options
		$this->settings['access_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Access and Publishing Controls',
			'std'    => 'Set code to access collection form and how to handlew new submissions.',
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

		$this->settings['new_item_status'] = array(
			'section' => 'general',
			'title'   => __( 'Status For New Items' ),
			'desc'    => __( 'Set to draft to moderate submissions' ),
			'type'    => 'radio',
			'std'     => 'publish',
			'choices' => array(
				'publish' => 'Publish immediately',
				'draft' => 'Set to draft',
			)
		);		

		$this->settings['notify'] = array(
			'title'   => __( 'Notification Emails' ),
			'desc'    => __( 'Send notifications to these addresses (separate multiple ones wth commas). They must have an Editor Role on this site. Leave empty to disable notifications.' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);
		
		// ------- sort options
		$this->settings['sort_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Index Page Sorting',
			'std'    => 'Set the order of items on home page, search results, and archives.',
			'type'    => 'heading'
		);


		$this->settings['sort_by'] = array(
			'section' => 'general',
			'title'   => __( 'Sort Items by'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'date',
			'choices' => array (
							'date' => 'Date Published',
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
							'DESC' => 'Descending',
							'ASC' => 'Ascending',
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
			'title'   => __( 'Description Editing Field'),
			'desc'    => __( 'Use a plain text entry field or rich text editor.'),
			'type'    => 'radio',
			'std'     => 's',
			'choices' => array (
							's' => 'Simple plain text input field (accepts hypertext link shortcode)',
							'r' => 'Rich text editor'
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

		$this->settings['authorcheck'] = array(
		'section' => 'general',
		'title' 	=> '' ,// Not used for headings.
		'desc'   => 'Author Account', 
		'std'    =>  trucollector_author_user_check( 'collector' ),
		'type'    => 'heading'
		);					

		$this->settings['jetpackcheck'] = array(
		'section' => 'general',
		'title' 	=> '' ,// Not used for headings.
		'desc'   => 'JetPack Post By Email', 
		'std'    =>  splot_jetpack_post_email_check (),
		'type'    => 'heading'
		);					

		$this->settings['postbyemail'] = array(
			'title'   => __( 'Post By Email Address' ),
			'desc'    => __( 'Email address set up for submitting new items by email; it can be associated withany  account on this site. We suggest creating a forwarding domain email address to the one generated by the Jetpack Post BY Email plugin. This info is not used anywhere, it is just here to store the email address for your own reference.' ),
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
		echo '<p>These settings manaage the behavior and appearance of your site. There are quite a few of them!</p>';		
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
			
					if ( $desc != '' ) echo '<br /><span class="description">' . $desc . '</span><br />';

				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="trucollector_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}


				break;

			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="trucollector_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . format_for_editor( $options[$id] ) . '</textarea>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;
				
			case 'medialoader':
			
				echo '<div id="uploader_' . $id . '">';

				if ( $options[$id] )  {
					$front_img = wp_get_attachment_image_src( $options[$id], 'radcliffe' );
					echo '<img id="previewimage_' . $id . '" src="' . $front_img[0] . '" width="640" height="300" alt="default thumbnail" />';
				} else {
					echo '<img id="previewimage_' . $id . '" src="http://placehold.it/640x300" alt="default header image" />';
				}

				echo '<input type="hidden" name="trucollector_options[' . $id . ']" id="' . $id . '" value="' . $options[$id]  . '" />
  <br /><input type="button" class="upload_image_button button-primary" name="_trucollector_button' . $id .'" id="_trucollector_button' . $id .'" data-options_id="' . $id  . '" data-uploader_title="Set Default Header Image" data-uploader_button_text="Select Image" value="Set/Change Image" />
</div><!-- uploader -->';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

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
	
	
	/* jQuery Tabs */
	public function scripts() {
		wp_print_scripts( 'jquery-ui-tabs' );
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
		return $options[$option];
	else
		return false;
}
?>