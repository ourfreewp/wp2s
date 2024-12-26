<?php
/**
 * Name: Extract PHP Extensions Data as Items
 */

function newsplicity_template_items_php_extensions()
{
	$loaded_extensions = get_loaded_extensions();

	// Returns an array with the names of all modules compiled and loaded

	$formatted_items = [];

	foreach ($loaded_extensions as $extension) {

		$extension_name = $extension;

		$page_name_suffix = '-php-extension';

		$page_name = sanitize_title($extension_name) . $page_name_suffix;

		if (substr($page_name, -strlen($page_name_suffix)) !== $page_name_suffix) {
			$page_name .= $page_name_suffix;
		}

		$page = get_posts([
				'name' => $page_name,
				'posts_per_page' => 1,
				'post_type' => 'page',
				'post_status' => 'publish'
		])[0];

		$page_title = $page->post_title;
		$page_excerpt = $page->post_excerpt;
		$page_name = $page->post_name;

		$formatted_items[] = [
			'title' => $page_title ? $page_title : $extension_name,
			'slug' => sanitize_title($extension_name) ? sanitize_title($extension_name) : '',
			'description' => $page_excerpt ? $page_excerpt : '',
			'learn_more_link' => get_permalink($page->ID) ? get_permalink($page->ID) : '',
		];
	}

	// sort
	usort($formatted_items, function ($a, $b) {
		return $a['title'] <=> $b['title'];
	});

	return $formatted_items;
}
