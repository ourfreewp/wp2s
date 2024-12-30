<?php

function onthewater_get_post_template_item_data($template_name, $post_id)
{

	$post_title = onthewater_get_the_title($post_id);
	$post_permalink = get_permalink($post_id);
	$post_thumbnail = onthewater_get_the_post_thumbnail($post_id);
	$post_excerpt = onthewater_get_the_excerpt($post_id);
	$post_term = onthewater_get_the_term($post_id);
	$post_byline = onthewater_get_the_byline($post_id);
	$post_dateline = onthewater_get_the_dates($post_id, false, true);

	$data = [];

	switch ($template_name) {
		case 'default':
			$data = [
				'title' => $post_title,
				'excerpt' => $post_excerpt,
				'permalink' => $post_permalink,
				'thumbnail' => $post_thumbnail,
				'term' => $post_term,
				'byline' => $post_byline,
				'dateline' => $post_dateline
			];
			break;
	}

	return $data;
}



