<?php

function onthewater_get_the_dates($post_id, $get_modified = false, $include_wrapper = true)
{
	$published_formatted = get_the_date('F j, Y', $post_id);
	$published_datetime  = get_the_date('c', $post_id);

	$published_dateline = sprintf(
		'<p class="wp-block-post-date">
				<span class="wp-block-post-date-prefix">Published</span><time datetime="%s">%s</time>
		</p>',
		esc_html($published_datetime),
		esc_html($published_formatted)
	);

	if ($get_modified) {
		$modified_formatted  = get_the_modified_date('F j, Y', $post_id);
		$modified_datetime   = get_the_modified_date('c', $post_id);


		$modified_dateline = sprintf(
			'<p class="wp-block-post-date %s">
				<span class="wp-block-post-date-prefix">Updated</span> <time datetime="%s">%s</time>
		</p>',
			$get_modified ? 'wp-block-post-date__modified-date' : 'wp-block-post-date__modified-date visually-hidden',
			esc_html($modified_datetime),
			esc_html($modified_formatted)
		);
	}

	$dateline = sprintf(
		'<div class="wp-block-onthewater-post-dates">
			%s
			%s
		</div>',
		$published_dateline,
		$modified_dateline ?? ''
	);

	if (!$include_wrapper) {
		$dateline = '<div class="wp-block-onthewater-dateline">' . $published_formatted . '</div>';
	}

	return $dateline;
}
