<?php

add_filter('mb_settings_pages', function ($settings_pages) {

	$setting_page = ODDNEWS_SETTINGS_ID_SLIDESHOWS;

	$settings_pages[] = [
		'menu_title' => __('Settings', 'oddnews'),
		'id' => $setting_page,
		'option_name' => ODDNEWS_SETTINGS_OPTION_SLIDESHOWS,
		'parent' => 'edit.php?post_type=slideshow',
		'capability' => 'publish_pages',
		'style' => 'no-boxes',
		'columns' => 1,
		'icon_url' => 'dashicons-admin-generic',
	];

	return $settings_pages;

}, 10, 1);

add_action('init', function () {
	register_post_type('slideshow', [
		'label'               => esc_html__('Slideshows', 'oddnews'),
		'labels'              => [
			'name'                     => esc_html__('Slideshows', 'oddnews'),
			'singular_name'            => esc_html__('Slideshow', 'oddnews'),
			'add_new'                  => esc_html__('Add New', 'oddnews'),
			'add_new_item'             => esc_html__('Add New Slideshow', 'oddnews'),
			'edit_item'                => esc_html__('Edit Slideshow', 'oddnews'),
			'new_item'                 => esc_html__('New Slideshow', 'oddnews'),
			'view_item'                => esc_html__('View Slideshow', 'oddnews'),
			'view_items'               => esc_html__('View Slideshows', 'oddnews'),
			'search_items'             => esc_html__('Search Slideshows', 'oddnews'),
			'not_found'                => esc_html__('No slideshows found.', 'oddnews'),
			'not_found_in_trash'       => esc_html__('No slideshows found in Trash.', 'oddnews'),
			'parent_item_colon'        => esc_html__('Parent Slideshow:', 'oddnews'),
			'all_items'                => esc_html__('All Slideshows', 'oddnews'),
			'archives'                 => esc_html__('Slideshow Archives', 'oddnews'),
			'attributes'               => esc_html__('Slideshow Attributes', 'oddnews'),
			'insert_into_item'         => esc_html__('Insert into slideshow', 'oddnews'),
			'uploaded_to_this_item'    => esc_html__('Uploaded to this slideshow', 'oddnews'),
			'featured_image'           => esc_html__('Thumbnail image', 'oddnews'),
			'set_featured_image'       => esc_html__('Set Thumbnail image', 'oddnews'),
			'remove_featured_image'    => esc_html__('Remove Thumbnail image', 'oddnews'),
			'use_featured_image'       => esc_html__('Use as Thumbnail image', 'oddnews'),
			'menu_name'                => esc_html__('Slideshows', 'oddnews'),
			'filter_items_list'        => esc_html__('Filter slideshows list', 'oddnews'),
			'filter_by_date'           => esc_html__('Filter slideshows by date', 'oddnews'),
			'items_list_navigation'    => esc_html__('Slideshows list navigation', 'oddnews'),
			'items_list'               => esc_html__('Slideshows list', 'oddnews'),
			'item_published'           => esc_html__('Slideshow published.', 'oddnews'),
			'item_published_privately' => esc_html__('Slideshow published privately.', 'oddnews'),
			'item_reverted_to_draft'   => esc_html__('Slideshow reverted to draft.', 'oddnews'),
			'item_scheduled'           => esc_html__('Slideshow scheduled.', 'oddnews'),
			'item_updated'             => esc_html__('Slideshow updated.', 'oddnews'),
			'text_domain'              => esc_html__('oddnews', 'oddnews'),
		],
		'description'         => '',
		'public'              => true,
		'hierarchical'        => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,
		'query_var'           => true,
		'can_export'          => false,
		'delete_with_user'    => false,
		'has_archive'         => 'slideshows',
		'rest_base'           => '',
		'show_in_menu'        => true,
		'menu_position'       => '',
		'menu_icon'           => 'dashicons-slides',
		'capability_type'     => 'post',
		'supports'            => ['title', 'thumbnail', 'editor', 'excerpt', 'author', 'revisions', 'custom-fields'],
		'rewrite'             => [
			'slug'       => 'slideshow',
			'with_front' => false,
		],
		'template_lock' => 'all',
		'taxonomies'          => ['post_tag', 'topic', 'attribute'],
	]);
});


add_filter('rwmb_meta_boxes', function ($field_groups) {


	$setting_page = ODDNEWS_SETTINGS_ID_SLIDESHOWS;

	$prefix = 'slideshow-';

	$slide_content_field = [
		'name' => 'Content',
		'type' => 'wysiwyg',
		'id' => $prefix . 'slide-content',
		'raw' => true,
		'placeholder' => 'Enter content...',
		'options' => [
			'media_buttons' => false,
			'drag_drop_upload' => false,
			'textarea_rows' => 5,
			'teeny' => true,
			'quicktags' => false,
			'placeholder' => 'Enter content...',
		],
		'editor_class' => 'rwmb-wysiwyg',
		'autosave' => 'true',
	];

	$slide_title_field = [
		'name' => 'Title',
		'type' => 'text',
		'id' => $prefix . 'slide-title',
		'placeholder' => 'Enter title...',
		'autosave' => 'true',
	];

	$slide_image_field = [
		'name' => 'Image',
		'type' => 'image_advanced',
		'id' => $prefix . 'slide-image',
		'placeholder' => 'Upload media...',
		'max_file_uploads' => 1,
		'force_delete' => false,
		'max_status' => false,
		'columns' => 2,
		'autosave' => 'true',
	];

	$slide_image_thumbnail_field = [
		'name' => 'Thumbnail (Optional)',
		'type' => 'image_advanced',
		'id' => $prefix . 'slide-image-thumbnail',
		'placeholder' => 'Upload media...',
		'max_file_uploads' => 1,
		'force_delete' => false,
		'max_status' => false,
		'columns' => 2,
		'autosave' => 'true',
	];

	$slide_full_width_divider = [
		'type' => 'divider',
		'columns' => 12,
	];

	$slide_fields = [
		$slide_image_field,
		$slide_image_thumbnail_field,
		$slide_full_width_divider,
		$slide_title_field,
		$slide_full_width_divider,
		$slide_content_field,
	];

	$field_groups[] = [
		'id' => 'slideshow',
		'title' => 'Slideshow',
		'post_types' => ['slideshow'],
		'context' => 'normal',
		'style' => 'default',
		'priority' => 'high',
		'default_state' => 'expanded',
		'autosave' => 'true',
		'class' => 'slideshow',
		'fields' => [
			[
				'type' => 'custom_html',
				'std' => 'Configure the slideshow.',
				'columns' => 12,
			],
			[
				'id' => $prefix . 'slides',
				'type' => 'group',
				'clone' => true,
				'sort_clone' => true,
				'collapsible' => true,
				'save_state' => true,
				'default_state' => 'expanded',
				'autosave' => true,
				'group_title' => 'Slide {#}',
				'add_button' => 'Add Slide',
				'class' => $prefix . 'slides',
				'fields' => $slide_fields,
				'columns' => 12,
			],
			$slide_full_width_divider,
			[
				'id' => $prefix . 'last-slide',
				'title' => 'Last Slide',
				'type' => 'group',
				'class' => $prefix . 'last-slide',
				'fields' => [
					[
						'type' => 'group',
						'id' => $prefix . 'last-slide-header',
						'class' => $prefix . 'last-slide-header',
						'fields' => [
							[
								'name' => 'Enable Last Slide',
								'type' => 'switch',
								'style' => 'square',
								'id' => $prefix . 'last-slide-toggle',
								'columns' => 3,

							],
							[
								'name' => 'Custom Last Slide',
								'type' => 'switch',
								'style' => 'square',
								'id' => $prefix . 'last-slide-toggle-custom',
								'columns' => 3,
							],
						],
					],
					[
						'type' => 'group',
						'id' => $prefix . 'last-slide-content',
						'class' => $prefix . 'last-slide-content',
						'visible' => [$prefix . 'last-slide-toggle-custom', 1],
						'fields' => [
							$slide_image_field,
							$slide_image_thumbnail_field,
							$slide_full_width_divider,
							$slide_title_field,
							$slide_full_width_divider,
							$slide_content_field,
						],
					]
				],
			]
		]
	];


	$field_groups[] = [
		'settings_pages' => [$setting_page],
		'tabs' => [
			'general' => [
				'label' => 'Last Slide',
				'icon' => '',
			],
		],
		'fields' => [
			[
				'type' => 'custom_html',
				'std' => '<h2 style="padding:0;">Last slide settings</h2>',
				'tab' => 'general',
			],
			[
				'type' => 'group',
				'title' => 'Last Slide Defaults',
				'id' => $prefix . 'last-slide-defaults',
				'class' => $prefix . 'last-slide-defaults',
				'tab' => 'general',
				'fields' => [
					[
						'type' => 'image_advanced',
						'name' => 'Image',
						'id' => $prefix . 'last-slide-image',
						'placeholder' => 'Upload media...',
						'max_file_uploads' => 1,
						'force_delete' => false,
						'max_status' => false,
						'columns' => 2,
						'autosave' => 'true',
					],
					[
						'type' => 'image_advanced',
						'name' => 'Thumbnail',
						'id' => $prefix . 'last-slide-image-thumbnail',
						'placeholder' => 'Upload media...',
						'max_file_uploads' => 1,
						'force_delete' => false,
						'max_status' => false,
						'columns' => 2,
						'autosave' => 'true',
					],
					[
						'name' => 'Title',
						'type' => 'text',
						'id' => $prefix . 'last-slide-title',
						'placeholder' => 'Enter title...',
						'autosave' => 'true',
					],
					[
						'name' => 'Content',
						'type' => 'wysiwyg',
						'id' => $prefix . 'last-slide-content',
						'raw' => true,
						'placeholder' => 'Enter content...',
						'options' => [
							'media_buttons' => false,
							'drag_drop_upload' => false,
							'textarea_rows' => 5,
							'teeny' => true,
							'quicktags' => false,
							'placeholder' => 'Enter content...',
						],
						'editor_class' => 'rwmb-wysiwyg',
						'autosave' => 'true',
					]
				],
			]
		],
	];

	return $field_groups;
});
