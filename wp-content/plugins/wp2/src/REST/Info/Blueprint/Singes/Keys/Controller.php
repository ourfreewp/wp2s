<?php
/**
 * Name: Get Item Keys
 */

function newsplicity_template_item_keys() {

	$template_data_json = get_option('newsplicity_template_data');

	// ensure is string

	if (!is_string($template_data_json)) {
		$template_data_json = '';
	}

	$template_data = json_decode($template_data_json);

	$template_data_keys = array_keys((array) $template_data);

	$manual_keys = [
		'php_extensions',
	];

	$template_data_keys = array_merge($template_data_keys, $manual_keys);

	sort($template_data_keys);

	$formated_keys = [];

	foreach ($template_data_keys as $key) {
		$formated_keys[$key] = $key;
	}

	$template_data_keys = $formated_keys;

	return $template_data_keys;
}