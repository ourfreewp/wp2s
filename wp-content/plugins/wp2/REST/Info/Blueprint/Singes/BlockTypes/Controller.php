<?php
/**
 * Name: Extract Block Types Data as Items
 */

function newsplicity_template_items_block_types()
{

	$template_data_json = get_option('newsplicity_template_data');

	$template_data = json_decode($template_data_json);

	$items = $template_data->block_types;

	$formatted_items = [];

	foreach ($items as $item) {

		$name = $item->name;

		if (!$name) {
			continue;
		}

		$item_category = explode('/', $name)[0];

		$item->category = $item_category;

		$item_name = explode('/', $name)[1];

		$item_page_name = sanitize_title($name);

		$item_page_suffix = '-block-type';

		if (substr($item_page_name, strlen($item_page_suffix) * -1) !== $item_page_suffix) {
			$item_page_name .= $item_page_suffix;
		}

		$item_page = get_posts([
			'name' => $item_page_name,
			'posts_per_page' => -1,
			'post_type' => 'page',
			'post_status' => ['publish','pending']
		])[0];

		$item_is_hidden = false;

		if (!$item_page) {
			$item_is_hidden = true;
		}

		$item_page_id = $item_page->ID;
		$item_page_title = $item_page->post_title;
		$item_page_excerpt = $item_page->post_excerpt;
		$item_page_name = $item_page->post_name;

		$item_title = $item->name ? $item->name : $item_name;
		$item_excerpt = $item->description ? $item->description : $item_page_excerpt;
		$item_permalink = get_permalink($item_page->ID);

		$formatted_items[] = [
			'page_id' => $item_page_id,
			'title' => $item_name ? $item_name : 'No Name',
			'slug' => $item_page_name ? $item_page_name : 'no-slug',
			'description' => $item_excerpt ? $item_excerpt : 'No description',
			'learn_more_link' => $item_permalink ? $item_permalink : '',
			'category' => $item_category,
			'is_hidden' => $item_is_hidden
		];
	}


	return $formatted_items;
}