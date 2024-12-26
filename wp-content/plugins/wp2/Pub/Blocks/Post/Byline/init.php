<?php

function onthewater_get_the_byline($post_id)
{
	$byline = '';

	$coauthors = get_coauthors($post_id);

	$byline_item_count = 0;

	if ($coauthors) {
		foreach ($coauthors as $author) {

			if (is_author($author->ID)) {
				continue;
			}

			$byline_item_count++;

			$byline_items = sprintf(
				'<li class="wp-block-onthewater-post-coauthor"><a href="%s" title="Posts by %s">%s</a></li>',
				get_author_posts_url($author->ID),
				$author->display_name,
				$author->display_name
			);
		}
	}

	$byline = sprintf(
		'<div class="wp-block-onthewater-post-byline"><ul class="wp-block-onthewater-post-coauthors">%s</ul></div>',
		$byline_items
	);

	// if there are no byline items, return an empty string

	if ($byline_item_count === 0) {
		$byline = '';
	}

	return $byline;
}


/**
 * Prints HTML for the post's author.
 */
function otw2_author_byline() {
	$byline = sprintf(
		/* translators: %s: post author. */
		esc_html_x( 'by %s', 'post author', 'otw2' ),
		'<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
	);
	echo '<span class="Meta-author">' . $byline . '</span>'; // WPCS: XSS OK.
}
