<?php
add_action('init', function () {
	register_post_type('article', [
		'label'               => esc_html__('Articles', 'oddnews'),
		'labels'              => [
			'name'                     => esc_html__('Articles', 'oddnews'),
			'singular_name'            => esc_html__('Article', 'oddnews'),
			'add_new'                  => esc_html__('Add New', 'oddnews'),
			'add_new_item'             => esc_html__('Add New Article', 'oddnews'),
			'edit_item'                => esc_html__('Edit Article', 'oddnews'),
			'new_item'                 => esc_html__('New Article', 'oddnews'),
			'view_item'                => esc_html__('View Article', 'oddnews'),
			'view_items'               => esc_html__('View Articles', 'oddnews'),
			'search_items'             => esc_html__('Search Articles', 'oddnews'),
			'not_found'                => esc_html__('No articles found.', 'oddnews'),
			'not_found_in_trash'       => esc_html__('No articles found in Trash.', 'oddnews'),
			'parent_item_colon'        => esc_html__('Parent Article:', 'oddnews'),
			'all_items'                => esc_html__('All Articles', 'oddnews'),
			'archives'                 => esc_html__('Article Archives', 'oddnews'),
			'attributes'               => esc_html__('Article Attributes', 'oddnews'),
			'insert_into_item'         => esc_html__('Insert into article', 'oddnews'),
			'uploaded_to_this_item'    => esc_html__('Uploaded to this article', 'oddnews'),
			'featured_image'           => esc_html__('Thumbnail image', 'oddnews'),
			'set_featured_image'       => esc_html__('Set Thumbnail image', 'oddnews'),
			'remove_featured_image'    => esc_html__('Remove Thumbnail image', 'oddnews'),
			'use_featured_image'       => esc_html__('Use as Thumbnail image', 'oddnews'),
			'menu_name'                => esc_html__('Articles', 'oddnews'),
			'filter_items_list'        => esc_html__('Filter articles list', 'oddnews'),
			'filter_by_date'           => esc_html__('Filter articles by date', 'oddnews'),
			'items_list_navigation'    => esc_html__('Articles list navigation', 'oddnews'),
			'items_list'               => esc_html__('Articles list', 'oddnews'),
			'item_published'           => esc_html__('Article published.', 'oddnews'),
			'item_published_privately' => esc_html__('Article published privately.', 'oddnews'),
			'item_reverted_to_draft'   => esc_html__('Article reverted to draft.', 'oddnews'),
			'item_scheduled'           => esc_html__('Article scheduled.', 'oddnews'),
			'item_updated'             => esc_html__('Article updated.', 'oddnews'),
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
		'has_archive'         => 'articles',
		'rest_base'           => 'articles',
		'show_in_menu'        => true,
		'menu_position'       => '',
		'menu_icon'           => 'dashicons-text-page',
		'capability_type'     => 'post',
		'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'author', 'custom-fields', 'revisions', 'page-attributes'],
		'rewrite'             => [
			'slug'       => 'article',
			'with_front' => false,
		],
		'taxonomies'          => ['post_tag', 'topic', 'attribute'],
		'template' => [
			[
				'core/paragraph',
				[
					'placeholder' => 'Start writing or type / to choose a block',
					'dropCap'     => false,
				],
			],
		],
	]);
});