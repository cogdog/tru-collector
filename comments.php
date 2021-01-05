<?php

if ( post_password_required() ) {
	return;
}

if ( have_comments() ) : ?>

	<div class="comments-container">

		<div class="comments-inner">

			<a name="comments"></a>

			<h2 class="comments-title">

				<?php
				$comment_count = get_comments_number();
				printf( _n( '%s ' . ucwords(get_trucollector_collection_single_comment_name()), '%s ' . ucwords(get_trucollector_collection_plural_comment_name()), $comment_count, 'fukasawa' ), $comment_count ); ?>

			</h2>

			<div class="comments">

				<ol class="commentlist">
				    <?php wp_list_comments( array( 'type' => 'comment', 'callback' => 'fukasawa_comment' ) ); ?>
				</ol>

				<?php if ( ! empty( $comments_by_type['pings'] ) ) : ?>

					<div class="pingbacks">

						<h3 class="pingbacks-title">

							<?php
							$pingback_count = count( $wp_query->comments_by_type['pings'] );
							printf( _n( '%s Pingback', '%s Pingbacks', $pingback_count, 'fukasawa' ), $pingback_count ); ?>

						</h3>

						<ol class="pingbacklist">
						    <?php wp_list_comments( array( 'type' => 'pings', 'callback' => 'fukasawa_comment' ) ); ?>
						</ol>

					</div><!-- .pingbacks -->

				<?php endif; ?>

				<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>

					<div class="comments-nav clear" role="navigation">

						<div class="fleft">
							<?php previous_comments_link( '&larr; ' . __( 'Older', 'fukasawa' ) . '<span> ' . __( 'Comments', 'fukasawa' ) . '</span>' ); ?>
						</div>

						<div class="fright">
							<?php next_comments_link( __( 'Newer', 'fukasawa' ) . '<span> ' . __( 'Comments','fukasawa' ) . '</span>' . ' &rarr;' ); ?>
						</div>

					</div><!-- .comments-nav-below -->

				<?php endif; ?>

			</div><!-- .comments -->

		</div><!-- .comments-inner -->

	</div><!-- .comments-container -->

	<?php
endif;

if ( comments_open() ) echo '<div class="respond-container">';

comment_form();

if ( comments_open() ) echo '</div><!-- .respond-container -->';

?>
