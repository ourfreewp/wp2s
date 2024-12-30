<?php
/**
 * Name: Extract Shortcodes as Items
 */

function newsplicity_template_items_shortcodes()
{

	$template_data_json = get_option('newsplicity_template_data');

	$template_data = json_decode($template_data_json);

	$items = $template_data->shortcodes;

	$formatted_items = [];

	foreach ($items as $item) {

		$title = $item;

		if ( !$item ) {
			continue;
		}

		$formatted_items[] = [
			'title' => $title ? $title : 'No Name',
			'description' => '',
			'featured_image' => '',
			'featured_image_alt' => '',
			'is_hidden' => false,
			'category' => '',
			'category_description' => '',
		];

	}

	return $formatted_items;
}