<?php

// Theme setup
add_action( 'after_setup_theme', 'fukasawa_setup' );

function fukasawa_setup() {
	
	// Automatic feed
	add_theme_support( 'automatic-feed-links' );
	
	// Set content-width
	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 620;
	
	// Post thumbnails
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size ( 88, 88, true );
	
	add_image_size( 'post-image', 973, 9999 );
	add_image_size( 'post-thumb', 508, 9999 );
	
	// Post formats
	add_theme_support( 'post-formats', array( 'gallery', 'image', 'video' ) );
		
	// Jetpack infinite scroll
	add_theme_support( 'infinite-scroll', array(
		'type' 				=> 		'click',
	    'container'			=> 		'posts',
		'footer' 			=> 		false,
	) );
	
	// Add nav menu
	register_nav_menu( 'primary', __('Primary Menu','fukasawa') );
	
	// Make the theme translation ready
	load_theme_textdomain('fukasawa', get_template_directory() . '/languages');
	
	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable($locale_file) )
	  require_once($locale_file);
	
}


// Register and enqueue Javascript files
function fukasawa_load_javascript_files() {

	if ( !is_admin() ) {		
		wp_enqueue_script( 'masonry' );
		wp_enqueue_script( 'fukasawa_flexslider', get_template_directory_uri().'/js/flexslider.min.js', array('jquery'), '', true );
		wp_enqueue_script( 'fukasawa_global', get_template_directory_uri().'/js/global.js', array('jquery'), '', true );
	}
}

add_action( 'wp_enqueue_scripts', 'fukasawa_load_javascript_files' );


// Register and enqueue styles
function fukasawa_load_style() {
	if ( !is_admin() ) {
	    wp_enqueue_style( 'fukasawa_googleFonts', '//fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic' );
	    wp_enqueue_style( 'fukasawa_genericons', get_stylesheet_directory_uri() . '/genericons/genericons.css' );
	    wp_enqueue_style( 'fukasawa_style', get_stylesheet_uri() );
	}
}

add_action('wp_print_styles', 'fukasawa_load_style');


// Add editor styles
function fukasawa_add_editor_styles() {
    add_editor_style( 'fukasawa-editor-styles.css' );
    $font_url = '//fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic';
    add_editor_style( str_replace( ',', '%2C', $font_url ) );
}
add_action( 'init', 'fukasawa_add_editor_styles' );


// Add sidebar widget area
add_action( 'widgets_init', 'fukasawa_sidebar_reg' ); 

function fukasawa_sidebar_reg() {
	register_sidebar(array(
	  'name' => __( 'Sidebar', 'fukasawa' ),
	  'id' => 'sidebar',
	  'description' => __( 'Widgets in this area will be shown in the sidebar.', 'fukasawa' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
	  'after_widget' => '</div><div class="clear"></div></div>'
	));
}


// Add theme widgets
require_once (get_template_directory() . "/widgets/dribbble-widget.php");  
require_once (get_template_directory() . "/widgets/flickr-widget.php");  
require_once (get_template_directory() . "/widgets/recent-comments.php");
require_once (get_template_directory() . "/widgets/recent-posts.php");
require_once (get_template_directory() . "/widgets/video-widget.php");


// Delist the WordPress widgets replaced by custom theme widgets
 function fukasawa_unregister_default_widgets() {
     unregister_widget('WP_Widget_Recent_Comments');
     unregister_widget('WP_Widget_Recent_Posts');
 }
 add_action('widgets_init', 'fukasawa_unregister_default_widgets', 11);


// Check whether the browser supports javascript
function html_js_class () {
    echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>'. "\n";
}
add_action( 'wp_head', 'html_js_class', 1 );


// Custom title function
function fukasawa_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title &mdash; $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title &mdash; " . sprintf( __( 'Page %s', 'fukasawa' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'fukasawa_wp_title', 10, 2 );


// Add classes to next_posts_link and previous_posts_link
add_filter('next_posts_link_attributes', 'fukasawa_posts_link_attributes_1');
add_filter('previous_posts_link_attributes', 'fukasawa_posts_link_attributes_2');

function fukasawa_posts_link_attributes_1() {
    return 'class="archive-nav-older fleft"';
}
function fukasawa_posts_link_attributes_2() {
    return 'class="archive-nav-newer fright"';
}


// Change the length of excerpts
function fukasawa_custom_excerpt_length( $length ) {
	return 28;
}
add_filter( 'excerpt_length', 'fukasawa_custom_excerpt_length', 999 );


// Change the excerpt ellipsis
function fukasawa_new_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'fukasawa_new_excerpt_more' );


// Add body class if is_mobile
add_filter('body_class','fukasawa_is_mobile_body_class');
 
function fukasawa_is_mobile_body_class( $classes ){
 
    /* using mobile browser */
    if ( wp_is_mobile() ){
        $classes[] = 'wp-is-mobile';
    }
    else{
        $classes[] = 'wp-is-not-mobile';
    }
    return $classes;
}


// Remove inline styling of attachment
add_shortcode('wp_caption', 'fukasawa_fixed_img_caption_shortcode');
add_shortcode('caption', 'fukasawa_fixed_img_caption_shortcode');

function fukasawa_fixed_img_caption_shortcode($attr, $content = null) {
	if ( ! isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
			$content = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	}
	
	$output = apply_filters('img_caption_shortcode', '', $attr, $content);
	
	if ( $output != '' ) return $output;
	extract(shortcode_atts(array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
	), $attr));
	
	if ( 1 > (int) $width || empty($caption) )
	return $content;
	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';
	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" >' 
	. do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
}


// Get comment excerpt length
function fukasawa_get_comment_excerpt($comment_ID = 0, $num_words = 20) {
	$comment = get_comment( $comment_ID );
	$comment_text = strip_tags($comment->comment_content);
	$blah = explode(' ', $comment_text);
	if (count($blah) > $num_words) {
		$k = $num_words;
		$use_dotdotdot = 1;
	} else {
		$k = count($blah);
		$use_dotdotdot = 0;
	}
	$excerpt = '';
	for ($i=0; $i<$k; $i++) {
		$excerpt .= $blah[$i] . ' ';
	}
	$excerpt .= ($use_dotdotdot) ? '...' : '';
	return apply_filters('get_comment_excerpt', $excerpt);
}


// Style the admin area
function fukasawa_admin_area_style() { 
   echo '
<style type="text/css">

	#postimagediv #set-post-thumbnail img {
		max-width: 100%;
		height: auto;
	}

</style>';
}

add_action('admin_head', 'fukasawa_admin_area_style');


// Flexslider function for format-gallery
function fukasawa_flexslider($size) {

	if ( is_page()) :
		$attachment_parent = $post->ID;
	else : 
		$attachment_parent = get_the_ID();
	endif;

	if($images = get_posts(array(
		'post_parent'    => $attachment_parent,
		'post_type'      => 'attachment',
		'numberposts'    => -1, // show all
		'post_status'    => null,
		'post_mime_type' => 'image',
                'orderby'        => 'menu_order',
                'order'           => 'ASC',
	))) { ?>
	
		<div class="flexslider">
		
			<ul class="slides">
	
				<?php foreach($images as $image) { 
				
					$attimg = wp_get_attachment_image($image->ID, $size); ?>
					
					<li>
						<?php echo $attimg; ?>
					</li>
					
				<?php }; ?>
		
			</ul>
			
		</div><?php
		
	}
}


// Fukasawa comment function
if ( ! function_exists( 'fukasawa_comment' ) ) :
function fukasawa_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	
		<?php __( 'Pingback:', 'fukasawa' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'fukasawa' ), '<span class="edit-link">', '</span>' ); ?>
		
	</li>
	<?php
			break;
		default :
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			
			<div class="comment-header">
			
				<?php echo get_avatar( $comment, 160 ); ?>
				
				<div class="comment-header-inner">
											
					<h4><?php echo get_comment_author_link(); ?></h4>
					
					<div class="comment-meta">
						<a class="comment-date-link" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" title="<?php echo get_comment_date() . ' at ' . get_comment_time(); ?>"><?php echo get_comment_date(get_option('date_format')) ?></a>
					</div> <!-- /comment-meta -->
				
				</div> <!-- /comment-header-inner -->
			
			</div>

			<div class="comment-content post-content">
			
				<?php comment_text(); ?>
				
			</div><!-- /comment-content -->
			
			<div class="comment-actions">
			
				<?php if ( '0' == $comment->comment_approved ) : ?>
				
					<p class="comment-awaiting-moderation fright"><?php _e( 'Your comment is awaiting moderation.', 'fukasawa' ); ?></p>
					
				<?php endif; ?>
				
				<div class="fleft">
			
				<?php 
					comment_reply_link( array( 
						'reply_text' 	=>  	__('Reply','fukasawa'),
						'depth'			=> 		$depth, 
						'max_depth' 	=> 		$args['max_depth'],
						'before'		=>		'',
						'after'			=>		''
						) 
					); 
				?><?php edit_comment_link( __( 'Edit', 'fukasawa' ), '<span class="sep">/</span>', '' ); ?>
				
				</div>
				
				<div class="clear"></div>
			
			</div> <!-- /comment-actions -->
										
		</div><!-- /comment-## -->
				
	<?php
		break;
	endswitch;
}
endif;


// Add and save meta boxes for posts
add_action( 'add_meta_boxes', 'fukasawa_cd_meta_box_add' );
function fukasawa_cd_meta_box_add() {
	add_meta_box( 'post-video-url', __('Video URL', 'fukasawa'), 'fukasawa_cd_meta_box_video_url', 'post', 'side', 'high' );
}

function fukasawa_cd_meta_box_video_url( $post ) {
	$values = get_post_custom( $post->ID );
	$video_url = isset( $values['video_url'] ) ? esc_attr( $values['video_url'][0] ) : '';
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
		<p>
			<input type="text" class="widefat" name="video_url" id="video_url" value="<?php echo $video_url; ?>" />
		</p>
	<?php		
}

add_action( 'save_post', 'fukasawa_cd_meta_box_save' );
function fukasawa_cd_meta_box_save( $post_id ) {
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
	
	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;
	
	// now we can actually save the data
	$allowed = array( 
		'a' => array( // on allow a tags
			'href' => array() // and those anchords can only have href attribute
		)
	);
	
	// Probably a good idea to make sure the data is set		
	if( isset( $_POST['video_url'] ) ) {
		update_post_meta( $post_id, 'video_url', wp_kses( $_POST['video_url'], $allowed ) );
	}
	
}


// Hide/show meta boxes depending on the post format selected
function fukasawa_meta_box_post_format_toggle()
{
    wp_enqueue_script( 'jquery' );

    $script = '
    <script type="text/javascript">
        jQuery( document ).ready( function($)
            {
            
                $( "#post-video-url" ).hide();
                $( "#post-quote-content-box" ).hide();
                $( "#post-quote-attribution-box" ).hide();
            	
            	if($("#post-format-video").is(":checked"))
	                $( "#post-video-url" ).show();
                
                $( "input[name=\"post_format\"]" ).change( function() {
	                $( "#post-video-url" ).hide();
                } );

                $( "input#post-format-video" ).change( function() {
                    $( "#post-video-url" ).show();
				});

            }
        );
    </script>';

    return print $script;
}
add_action( 'admin_footer', 'fukasawa_meta_box_post_format_toggle' );


// Fukasawa theme options
class fukasawa_Customize {

   public static function fukasawa_register ( $wp_customize ) {
   
      //1. Define a new section (if desired) to the Theme Customizer
      $wp_customize->add_section( 'fukasawa_options', 
         array(
            'title' => __( 'Options for Fukasawa', 'fukasawa' ), //Visible title of section
            'priority' => 35, //Determines what order this appears in
            'capability' => 'edit_theme_options', //Capability needed to tweak
            'description' => __('Allows you to customize theme settings for Fukasawa.', 'fukasawa'), //Descriptive tooltip
         ) 
      );
      
      $wp_customize->add_section( 'fukasawa_logo_section' , array(
		    'title'       => __( 'Logo', 'fukasawa' ),
		    'priority'    => 40,
		    'description' => __('Upload a logo to replace the default site title in the sidebar/header', 'fukasawa'),
	  ) );
      
      
      //2. Register new settings to the WP database...
      $wp_customize->add_setting( 'accent_color', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
         array(
            'default' => '#019EBD', //Default setting/value to save
            'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
            'transport' => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
         ) 
      );
	  
	  $wp_customize->add_setting( 'fukasawa_logo', 
      	array( 
      		'sanitize_callback' => 'esc_url_raw'
      	) 
      );
      
      
      //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
      $wp_customize->add_control( new WP_Customize_Color_Control( //Instantiate the color control class
         $wp_customize, //Pass the $wp_customize object (required)
         'fukasawa_accent_color', //Set a unique ID for the control
         array(
            'label' => __( 'Accent Color', 'fukasawa' ), //Admin-visible name of the control
            'section' => 'colors', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
            'settings' => 'accent_color', //Which setting to load and manipulate (serialized is okay)
            'priority' => 10, //Determines the order this control appears in for the specified section
         ) 
      ) );
      
      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'fukasawa_logo', array(
		    'label'    => __( 'Logo', 'fukasawa' ),
		    'section'  => 'fukasawa_logo_section',
		    'settings' => 'fukasawa_logo',
	  ) ) );
      
      //4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
      $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
      $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
   }

   public static function fukasawa_header_output() {
      ?>
      
	      <!-- Customizer CSS --> 
	      
	      <style type="text/css">
	           <?php self::fukasawa_generate_css('body a', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('body a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.main-menu .current-menu-item:before', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.main-menu .current_page_item:before', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget-content .textwidget a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_fukasawa_recent_posts a:hover .title', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_fukasawa_recent_comments a:hover .title', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_archive li a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_categories li a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_meta li a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_nav_menu li a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_rss .widget-content ul a.rsswidget:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('#wp-calendar thead', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_tag_cloud a:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.search-button:hover .genericon', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.flex-direction-nav a:hover', 'background-color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('a.post-quote:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.posts .post-title a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content a', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content a:hover', 'border-bottom-color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content blockquote:before', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content fieldset legend', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content input[type="submit"]:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content input[type="button"]:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content input[type="reset"]:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.page-links a:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.comments .pingbacks li a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.comment-header h4 a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.bypostauthor.commet .comment-header:before', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.form-submit #submit:hover', 'background-color', 'accent_color'); ?>
	           
	           <?php self::fukasawa_generate_css('.nav-toggle.active', 'background-color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.mobile-menu .current-menu-item:before', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.mobile-menu .current_page_item:before', 'color', 'accent_color'); ?>
	           
	           <?php self::fukasawa_generate_css('body#tinymce.wp-editor a', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('body#tinymce.wp-editor a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('body#tinymce.wp-editor fieldset legend', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('body#tinymce.wp-editor blockquote:before', 'color', 'accent_color'); ?>
	      </style> 
	      
	      <!--/Customizer CSS-->
	      
      <?php
   }
   
   public static function fukasawa_live_preview() {
      wp_enqueue_script( 
           'fukasawa-themecustomizer', // Give the script a unique ID
           get_template_directory_uri() . '/js/theme-customizer.js', // Define the path to the JS file
           array(  'jquery', 'customize-preview' ), // Define dependencies
           '', // Define a version (optional) 
           true // Specify whether to put in footer (leave this true)
      );
   }

   public static function fukasawa_generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }
}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'fukasawa_Customize' , 'fukasawa_register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'fukasawa_Customize' , 'fukasawa_header_output' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init' , array( 'fukasawa_Customize' , 'fukasawa_live_preview' ) );

?>