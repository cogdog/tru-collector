<?php get_header(); ?>

<div class="content">
																	                    
	<?php if ( have_posts() ) :

		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$archive_title = '';
		$archive_subtitle = '';

		if ( is_archive() ) {
			$archive_title = get_the_archive_title();
		} elseif ( is_search() ) {
			$archive_title = sprintf( _x( 'Search results: "%s"', 'Variable: search query text', 'fukasawa' ), get_search_query() );
		} elseif ( $paged > 1 ) {
			$archive_title = sprintf( __( 'Page %1$s of %2$s', 'fukasawa' ), $paged, $wp_query->max_num_pages );
		}

		if ( ( is_archive() || is_search() ) && 1 < $wp_query->max_num_pages ) {
			$archive_subtitle = sprintf( __( 'Page %1$s of %2$s', 'fukasawa' ), $paged, $wp_query->max_num_pages );
		}
	
		if ( $archive_title ) : ?>

			<div class="page-title">
				
				<div class="section-inner">
		
					<h4>
						<?php 
						echo $archive_title;
						
						if ( $archive_subtitle ) {
							echo '<span>' . $archive_subtitle . '</span>';
						} 
						?>

						<div class="clear"></div>
						
					</h4>
							
				</div><!-- .section-inner -->
				
			</div><!-- .page-title -->

		<?php endif; ?>
	
		<div class="posts" id="posts">

			<div class="grid-sizer"></div>
				
			<?php 
			while ( have_posts() ) : the_post();
			
				get_template_part( 'content', get_post_format() );
				
			endwhile; 
			?>
		
		</div><!-- .posts -->

	<?php endif; ?>
	
	<?php if ( $wp_query->max_num_pages > 1 ) : ?>
		
		<div class="archive-nav">
				
			<?php 
			
				$nav_label = get_trucollector_collection_plural_item();
				
				echo get_next_posts_link( __( 'Older ' . $nav_label , 'fukasawa' ) . ' &rarr;' ); 
				
				echo get_previous_posts_link( '&larr; ' . __( 'Newer ' . $nav_label, 'fukasawa' ) ); 
				
			?>
			
			<div class="clear"></div>
						
		</div><!-- .archive-nav -->
						
	<?php endif; ?>
		
</div><!-- .content -->
	              	        
<?php get_footer(); ?>