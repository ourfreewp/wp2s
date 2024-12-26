<?php

add_action('init', function () {

	$post_types = get_post_types(['public' => true], 'names');

	register_taxonomy('attribute', $post_types, [
		'label'              => esc_html__('Attributes', 'oddnews'),
		'labels'             => [
			'name'                       => esc_html__('Attributes', 'oddnews'),
			'singular_name'              => esc_html__('Attribute', 'oddnews'),
			'menu_name'                  => esc_html__('Attributes', 'oddnews'),
			'search_items'               => esc_html__('Search Attributes', 'oddnews'),
			'popular_items'              => esc_html__('Popular Attributes', 'oddnews'),
			'all_items'                  => esc_html__('All Attributes', 'oddnews'),
			'parent_item'                => esc_html__('Parent Attribute', 'oddnews'),
			'parent_item_colon'          => esc_html__('Parent Attribute:', 'oddnews'),
			'edit_item'                  => esc_html__('Edit Attribute', 'oddnews'),
			'view_item'                  => esc_html__('View Attribute', 'oddnews'),
			'update_item'                => esc_html__('Update Attribute', 'oddnews'),
			'add_new_item'               => esc_html__('Add New Attribute', 'oddnews'),
			'new_item_name'              => esc_html__('New Attribute Name', 'oddnews'),
			'separate_items_with_commas' => esc_html__('Separate attributes with commas', 'oddnews'),
			'add_or_remove_items'        => esc_html__('Add or remove attributes', 'oddnews'),
			'choose_from_most_used'      => esc_html__('Choose most used attributes', 'oddnews'),
			'not_found'                  => esc_html__('No attributes found.', 'oddnews'),
			'no_terms'                   => esc_html__('No attributes', 'oddnews'),
			'filter_by_item'             => esc_html__('Filter by attribute', 'oddnews'),
			'items_list_navigation'      => esc_html__('Attributes list pagination', 'oddnews'),
			'items_list'                 => esc_html__('Attributes list', 'oddnews'),
			'most_used'                  => esc_html__('Most Used', 'oddnews'),
			'back_to_items'              => esc_html__('&larr; Go to Attributes', 'oddnews'),
			'text_domain'                => esc_html__('oddnews', 'oddnews'),
		],
		'description'        => '',
		'public'             => true,
		'publicly_queryable' => false,
		'hierarchical'       => false,
		'show_ui'            => false,
		'show_in_menu'       => false,
		'show_in_nav_menus'  => true,
		'show_in_rest'       => true,
		'show_tagcloud'      => false,
		'show_in_quick_edit' => false,
		'show_admin_column'  => false,
		'query_var'          => true,
		'sort'               => false,
		'rest_base'          => 'attributes',
		'rewrite'            => [
			'slug'         => 'attribute',
			'with_front'   => false,
			'hierarchical' => false,
		],
	]);
});




	$post_types = get_post_types(['public' => true], 'names');

	$taxonomy = 'onthewater_attribute';

	register_taxonomy($taxonomy, $post_types, [
		'label'        => 'Attributes',
		'public'       => true,
		'publicly_queryable' => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'show_in_rest' => true,
		'rest_base'    => 'attributes',
		'rewrite'      => [
			'slug' => 'attributes',
		],
		'hierarchical'  => true,
		'show_admin_column' => true,
	]);

	add_filter('rwmb_meta_boxes', function ($meta_boxes) use ($taxonomy) {

		$meta_boxes[] = [
			'title'      => 'Attribute Alerts',
			'taxonomies' => [$taxonomy],
			'style'      => 'seamless',
			'fields'     => [
				[
					'type' => 'group',
					'id'   => 'alerts',
					'name' => '',
					'clone' => true,
					'sort_clone' => true,
					'group_title' => 'Alert {#}',
					'fields' => [
						[
							'id'   => 'type',
							'type' => 'select',
							'options' => [
								'primary' => 'Primary',
								'secondary' => 'Secondary',
								'success' => 'Success',
								'danger' => 'Danger',
								'warning' => 'Warning',
								'info'    => 'Info',
								'light'   => 'Light',
								'dark'    => 'Dark',
							],
							'std'  => 'info',
						],
						[
							'id'   => 'title',
							'type' => 'text',
							'placeholder' => 'Title (optional)',
						],
						[
							'id'   => 'icon',
							'type' => 'icon',
						],
						[
							'id'   => 'content',
							'type' => 'wysiwyg',
							'required' => true,
							'options' => [
								'textarea_rows' => 4,
								'teeny' => true,
								'media_buttons' => false,
								'quicktags' => false,
								'dfw' => false,
							],
						],
					],
				]
			],
		];

		return $meta_boxes;
	});


