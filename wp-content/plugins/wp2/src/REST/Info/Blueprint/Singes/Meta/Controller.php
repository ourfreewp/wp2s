<?php
add_filter('rwmb_meta_boxes', function ($meta_boxes) {

	$prefix = 'newsplicity_';

	$meta_boxes[] = [
		'title' => __('Template Data', 'newsplicity'),
		'id' => $prefix . 'template_data',
		'post_types' => ['page'],
		'fields' => [
			[
				'name' => __('Name', 'newsplicity'),
				'id' => $prefix . 'template_data_name',
				'type' => 'select',
				'options' => newsplicity_template_item_keys()
			],
		],
	];

	return $meta_boxes;
});

