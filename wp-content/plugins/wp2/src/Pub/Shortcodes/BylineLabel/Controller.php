<?php

add_shortcode('BylineLabel', function ($atts) {

	$byline_label = '';

	$byline_singular = 'Author';

	$byline_plural = 'Authors';

	if (function_exists('get_coauthors')) {
		$coauthors = get_coauthors();
		$coauthors_count = count($coauthors);
	} else {
		$coauthors_count = 1;
	}

	if (get_post_type() == 'video') {
		$byline_singular = 'Producer';
		$byline_plural = 'Producers';
	} elseif (get_post_type() == 'slideshow') {
		$byline_singular = 'Creator';
		$byline_plural = 'Creators';
	}

	if ($coauthors_count > 1) {
		$byline_label = $byline_plural;
	} else {
		$byline_label = $byline_singular;
	}

	return $byline_label;

});
