<?php

/**
 * Block Name: Search Results Summary
 */

if ($isEditor) {
	$summary = 'Showing X of XX results';
} else {

	if (!is_search()) {
		return;
	}

	global $wp_query;

	$total_results = $wp_query->found_posts ? $wp_query->found_posts : 0;

	$total_showing = $wp_query->post_count ? $wp_query->post_count : 0;

	$summary = 'Showing %s of %s results';

	if ($total_results === 0) {
		$summary = 'No results found';
	} elseif ($total_results === 1) {
		$summary = 'Showing 1 result';
	} else {
		$summary = sprintf($summary, $total_showing, $total_results);
	}
}

?>

<p useBlockProps><?php echo esc_html($summary); ?></p>