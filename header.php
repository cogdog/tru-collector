<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

	<head profile="http://gmpg.org/xfn/11">
		
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
				
		<?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>
		 
		<?php wp_head(); ?>
	
	</head>
	
	<body <?php body_class(); ?>>
	
		<div class="mobile-navigation">
	
			<ul class="mobile-menu">
						
				<?php if ( has_nav_menu( 'primary' ) ) {
																	
					wp_nav_menu( array( 
					
						'container' => '', 
						'items_wrap' => '%3$s',
						'theme_location' => 'primary'
													
					) ); } else {
				
					echo splot_default_menu();
					
					
				} ?>
				
			 </ul>
		 
		</div> <!-- /mobile-navigation -->
	
		<div class="sidebar">
		
			<?php if ( get_theme_mod( 'fukasawa_logo' ) ) : ?>
			
		        <a class="blog-logo" href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'title' ) ); ?> &mdash; <?php echo esc_attr( get_bloginfo( 'description' ) ); ?>' rel='home'>
		        	<img src='<?php echo esc_url( get_theme_mod( 'fukasawa_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'title' ) ); ?>'>
		        </a>
		        
		       
		
			<?php elseif ( get_bloginfo( 'description' ) || get_bloginfo( 'title' ) ) : ?>
		
				<h1 class="blog-title">
					<a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'title' ) ); ?> &mdash; <?php echo esc_attr( get_bloginfo( 'description' ) ); ?>" rel="home"><?php echo esc_attr( get_bloginfo( 'title' ) ); ?></a>
				</h1>
				
				 <div class="siteblurb"><?php echo esc_attr( get_bloginfo( 'description' ) ); ?></div>
				 
			<?php endif; ?>
			
			<div class="siteblurb"><strong><?php echo wp_count_posts()->publish; ?></strong> total items collected</div>
			
			<a class="nav-toggle hidden" title="<?php _e('Click to view the navigation','fukasawa') ?>" href="#">
			
				<div class="bars">
				
					<div class="bar"></div>
					<div class="bar"></div>
					<div class="bar"></div>
					
					<div class="clear"></div>
				
				</div>
				
				<p>
					<span class="menu"><?php _e('Menu','fukasawa') ?></span>
					<span class="close"><?php _e('Close','fukasawa') ?></span>
				</p>
			
			</a>
			
			<ul class="main-menu">
				
				<?php if ( has_nav_menu( 'primary' ) ) {
																	
					wp_nav_menu( array( 
					
						'container' => '', 
						'items_wrap' => '%3$s',
						'theme_location' => 'primary'
													
					) ); 
					
					
					// test uf primary menu location is not set	
					} elseif ( !splot_is_menu_location_used() ) {
						echo splot_default_menu();
					
					// normal make menus from pages	
					
					
					} else {
				
					wp_list_pages( array(
					
						'container' => '',
						'title_li' => ''
					
					));
					
				} ?>
				
			 </ul>
			 
			 <div class="widgets">
			 
			 	<?php dynamic_sidebar('sidebar'); ?>
			 
			 </div>
			 
			 <div class="credits">
			 
			 	<p>A <a href="http://splot.ca/">SPLOT</a> on the Web: <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo('name'); ?></a>.</p>
			 	<p><?php _e('Blame','fukasawa'); ?> <a href="http://cogdog.info">cogdog</a>.</p>
			 	<p><?php _e('Based on Fukasawa theme by ','fukasawa'); ?> <a href="http://www.andersnoren.se">Anders Nor&eacute;n</a>.</p>
			 	<p>
			 	<?php splot_the_author();?>
				</p>
			 				 	
			 </div>
			
			 <div class="clear"></div>
							
		</div> <!-- /sidebar -->
	
		<div class="wrapper" id="wrapper">