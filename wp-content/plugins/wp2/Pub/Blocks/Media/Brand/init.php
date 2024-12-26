<?php

add_filter('mb_settings_pages', function ($settings_pages) {


	$settings_pages[] = [
		'menu_title' => __('Branding', 'oddnews'),
		'id'         => ODDNEWS_SETTINGS_ID_BRANDING,
		'position'   => 2,
		'parent'     => 'options-general.php',
		'capability' => 'publish_pages',
		'style'      => 'no-boxes',
		'columns'    => 1,
		'option_name' => ODDNEWS_SETTINGS_OPTION_BRANDING,
		'icon_url'   => 'dashicons-admin-generic',

	];

	return $settings_pages;
},	10, 1);

add_filter('rwmb_meta_boxes', function ($meta_boxes) {

	$setting_page = ODDNEWS_SETTINGS_ID_BRANDING;

	$brand_image_variations = [
		'mobile' => 'Mobile',
		'desktop' => 'Desktop',
	];

	$brand_image_fields = [];

	foreach ($brand_image_variations as $variation_id => $variation_name) {
		$brand_image_fields[] = [
			'id'               => 'brand_image_' . $variation_id,
			'name'             => $variation_name,
			'type'             => 'image_upload',
			'force_delete'     => true,
			'max_file_uploads' => 1,
			'max_status'       => false,
			'image_size'       => 'medium',
			'tab'              => 'media',
		];
	}

	$meta_boxes[] = [
		'settings_pages' => [$setting_page],
		'fields'         => $brand_image_fields,
		'tab_style'      => 'left',
		'tabs'       => [
			'media' => [
				'label' => __('Media', 'oddnews'),
			]
		],
	];

	return $meta_boxes;
});
