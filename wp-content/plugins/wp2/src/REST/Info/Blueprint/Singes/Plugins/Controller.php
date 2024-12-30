<?php
/**
 * Name: Extract Plugin Data as Items
 */

function newsplicity_template_items_plugins()
{

	$template_data_json = get_option('newsplicity_template_data');

	$template_data = json_decode($template_data_json);

	$items = $template_data->plugins;

	$formatted_items = [];

	foreach ($items as $item) {

		$text_domain = $item;

		if (!$text_domain) {
			continue;
		}

		$plugin_page_name = $text_domain;

		if (substr($plugin_page_name, -7) !== '-plugin') {
			$plugin_page_name .= '-plugin';
		}

		$plugin_page = get_posts([
				'name' => $plugin_page_name,
				'posts_per_page' => 1,
				'post_type' => 'page',
				'post_status' => 'publish'
		])[0];

		if (!$plugin_page) {
			continue;
		}

		$plugin_page_id = $plugin_page->ID;
		$plugin_page_title = $plugin_page->post_title;
		$plugin_page_excerpt = $plugin_page->post_excerpt;
		$plugin_page_name = $plugin_page->post_name;

		$plugin_title     = $item ? $item : $plugin_page_title;
		$plugin_excerpt   = $plugin_page_excerpt ? $plugin_page_excerpt : '';
		$plugin_permalink = get_permalink($plugin_page->ID) ? get_permalink($plugin_page->ID) : '';

		$formatted_items[] = [
			'page_id' => $plugin_page_id,
			'title' => $plugin_title ? $plugin_title : 'No title',
			'slug' => $plugin_page_name ? $plugin_page_name : 'no-slug',
			'description' => $plugin_excerpt ? $plugin_excerpt : 'No description',
			'learn_more_link' => $plugin_permalink ? $plugin_permalink : '',
		];
	}


	return $formatted_items;
}