<?php

add_filter('register_post_post_type_args', function ($args) {

	$args['has_archive'] = 'blog';

	$args['rewrite'] = [
		'slug'       => 'blog',
		'with_front' => false,
	];

	return $args;
},  20, 2);


add_action('init', function () {
	register_taxonomy('blog', ['post'], [
		'label'              => esc_html__('Blogs', 'oddnews'),
		'labels'             => [
			'name'                       => esc_html__('Blogs', 'oddnews'),
			'singular_name'              => esc_html__('Blog', 'oddnews'),
			'menu_name'                  => esc_html__('Blogs', 'oddnews'),
			'search_items'               => esc_html__('Search Blogs', 'oddnews'),
			'popular_items'              => esc_html__('Popular Blogs', 'oddnews'),
			'all_items'                  => esc_html__('All Blogs', 'oddnews'),
			'parent_item'                => esc_html__('Parent Blog', 'oddnews'),
			'parent_item_colon'          => esc_html__('Parent Blog:', 'oddnews'),
			'edit_item'                  => esc_html__('Edit Blog', 'oddnews'),
			'view_item'                  => esc_html__('View Blog', 'oddnews'),
			'update_item'                => esc_html__('Update Blog', 'oddnews'),
			'add_new_item'               => esc_html__('Add New Blog', 'oddnews'),
			'new_item_name'              => esc_html__('New Blog Name', 'oddnews'),
			'separate_items_with_commas' => esc_html__('Separate blogs with commas', 'oddnews'),
			'add_or_remove_items'        => esc_html__('Add or remove blogs', 'oddnews'),
			'choose_from_most_used'      => esc_html__('Choose most used blogs', 'oddnews'),
			'not_found'                  => esc_html__('No blogs found.', 'oddnews'),
			'no_terms'                   => esc_html__('No blogs', 'oddnews'),
			'filter_by_item'             => esc_html__('Filter by blog', 'oddnews'),
			'items_list_navigation'      => esc_html__('Blogs list pagination', 'oddnews'),
			'items_list'                 => esc_html__('Blogs list', 'oddnews'),
			'most_used'                  => esc_html__('Most Used', 'oddnews'),
			'back_to_items'              => esc_html__('&larr; Go to Blogs', 'oddnews'),
			'text_domain'                => esc_html__('oddnews', 'oddnews'),
		],
		'description'        => '',
		'public'             => true,
		'publicly_queryable' => false,
		'hierarchical'       => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => true,
		'show_in_rest'       => true,
		'show_tagcloud'      => true,
		'show_in_quick_edit' => true,
		'show_admin_column'  => false,
		'query_var'          => true,
		'sort'               => false,
		'meta_box_cb'        => 'post_categories_meta_box',
		'rest_base'          => 'blog',
		'rewrite'            => [
			'slug'         => 'blog',
			'with_front'   => false,
			'hierarchical' => false,
		],
	]);
}, 0);
