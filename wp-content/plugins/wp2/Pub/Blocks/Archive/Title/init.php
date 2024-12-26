<?php
add_filter(
	'get_the_archive_title',
	function ($title) {
		if (is_category()) {
			$title = single_cat_title('', false);
		} elseif (is_tag()) {
			$title = single_tag_title('', false);
		} elseif (is_author()) {
			$title = get_the_author();
		} elseif (is_post_type_archive()) {
			$title = post_type_archive_title('', false);
		} elseif (is_tax()) {
			$title = single_term_title('', false);
		}

		return $title;
	}
);


function onthewater_get_archive_title($post_id = null)
{

	$archive_title = get_the_archive_title();

	return $archive_title;
}

/**
 * Prints title for archive pages
 */
function otw2_archive_title() {
	$term = get_queried_object();
	if ( ! $term ) {
		return;
	}
	$format = '<h1 class="Page-title">%1$s%2$s</h1>';

	switch ( get_query_var( 'post_type' ) ) {
		case 'post':
			$post_type = ' Articles';
			break;
		case 'video':
			$post_type = ' Videos';
			break;
		case 'forecasts':
			$post_type = ' Reports';
			break;
		case 'news':
			$post_type = ' News';
			break;
		default:
			$post_type = '';
	}

	if ( is_tag() ) {
		$term_name = apply_filters( 'single_tag_title', $term->name );
		$title = sprintf( $format, $term_name, $post_type );
	} elseif ( is_author() ) {
		$term_name = $term->display_name;
		$title = sprintf( $format, $term_name, $post_type );
	} elseif ( is_tax() ) {
		$term_name = apply_filters( 'single_term_title', $term->name );
		$title = sprintf( $format, $term_name, $post_type );
	} elseif ( is_category() ) {
		$term_name = apply_filters( 'single_cat_title', $term->name );
		$title = sprintf( $format, $term_name, $post_type );
	} else {
		return;
	}

	if ( empty( $term_name ) ) {
		return;
	}
	global $allowedposttags;
	echo wp_kses( $title, $allowedposttags );
}
