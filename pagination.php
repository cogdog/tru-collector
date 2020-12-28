<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<div class="archive-nav clear">
		<?php
			echo get_next_posts_link( __( 'Previous ' . get_trucollector_collection_plural_item() , 'fukasawa' ) . ' &rarr;' );
			echo get_previous_posts_link( '&larr; ' . __( 'Next ' . get_trucollector_collection_plural_item(), 'fukasawa' ) );
	?>
	</div><!-- .archive-nav -->
<?php endif?>
