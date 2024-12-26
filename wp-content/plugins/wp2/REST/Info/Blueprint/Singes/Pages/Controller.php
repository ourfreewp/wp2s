<?php
/**
 * Name: Extract Page Data as Items
 */

function newsplicity_template_items_pages() {

	$template_data_json = get_option('newsplicity_template_data');

	$template_data = json_decode($template_data_json);

	$items = $template_data->pages;

	$formatted_items = [];

	foreach ($items as $item) {

		$preview_link    = 'https://template.sites.newsplicity.dev/' . $item;

		$formatted_items[] = [
			'title' => $item ? $item : 'No title',
			'description' => '',
			'slug' => $item ? $item : 'no-slug',
			'learn_more_link' => '',
			'preview_link' => $preview_link,
		];
	}

	return $formatted_items;
}