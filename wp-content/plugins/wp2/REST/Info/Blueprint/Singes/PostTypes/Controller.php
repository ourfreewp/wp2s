<?php
/**
 * Name: Extract Post Types Data as Items
 */

function newsplicity_template_items_post_types()
{

	$template_data_json = get_option('newsplicity_template_data');

	$template_data = json_decode($template_data_json);

	$items = $template_data->post_types;

	$formatted_items = [];

	foreach ($items as $item) {

		$item_name = $item;

		$formatted_items[] = [
			'title' => $item_name ? $item_name : 'No Name',
		];

	}

	return $formatted_items;
}