<?php
add_action('init', function () {

	register_taxonomy('post-state', ['page'], [
		'label'              => esc_html__('Post States', 'oddnewsshow'),
		'labels'             => [
			'name'                       => esc_html__('Post States', 'oddnewsshow'),
			'singular_name'              => esc_html__('Post State', 'oddnewsshow'),
			'menu_name'                  => esc_html__('Post States', 'oddnewsshow'),
			'search_items'               => esc_html__('Search Post States', 'oddnewsshow'),
			'popular_items'              => esc_html__('Popular Post States', 'oddnewsshow'),
			'all_items'                  => esc_html__('All Post States', 'oddnewsshow'),
			'parent_item'                => esc_html__('Parent Post State', 'oddnewsshow'),
			'parent_item_colon'          => esc_html__('Parent Post State:', 'oddnewsshow'),
			'edit_item'                  => esc_html__('Edit Post State', 'oddnewsshow'),
			'view_item'                  => esc_html__('View Post State', 'oddnewsshow'),
			'update_item'                => esc_html__('Update Post State', 'oddnewsshow'),
			'add_new_item'               => esc_html__('Add New Post State', 'oddnewsshow'),
			'new_item_name'              => esc_html__('New Post State Name', 'oddnewsshow'),
			'separate_items_with_commas' => esc_html__('Separate post states with commas', 'oddnewsshow'),
			'add_or_remove_items'        => esc_html__('Add or remove post states', 'oddnewsshow'),
			'choose_from_most_used'      => esc_html__('Choose most used post states', 'oddnewsshow'),
			'not_found'                  => esc_html__('No post states found.', 'oddnewsshow'),
			'no_terms'                   => esc_html__('No post states', 'oddnewsshow'),
			'filter_by_item'             => esc_html__('Filter by post state', 'oddnewsshow'),
			'items_list_navigation'      => esc_html__('Post States list pagination', 'oddnewsshow'),
			'items_list'                 => esc_html__('Post States list', 'oddnewsshow'),
			'most_used'                  => esc_html__('Most Used', 'oddnewsshow'),
			'back_to_items'              => esc_html__('&larr; Go to Post States', 'oddnewsshow'),
			'text_domain'                => esc_html__('oddnewsshow', 'oddnewsshow'),
		],
		'description'        => '',
		'public'             => false,
		'publicly_queryable' => false,
		'hierarchical'       => false,
		'show_ui'            => false,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => true,
		'show_in_rest'       => true,
		'show_tagcloud'      => true,
		'show_in_quick_edit' => true,
		'show_admin_column'  => false,
		'query_var'          => true,
		'sort'               => false,
		'meta_box_cb'        => 'post_tags_meta_box',
		'rest_base'          => '',
		'rewrite'            => [
			'with_front'   => false,
			'hierarchical' => false,
		],
	]);
});

add_filter(
	'display_post_states',
	function ($post_states) {

		global $post;

		// reset post states
		$post_states = [];

		$current_post_name = $post->post_name;

		// blog set to 'Blog'
		if ('blog' === $current_post_name) {
			$post_states['blog'] = 'Archive';
		}

		// home set to 'Home'
		if ('home' === $current_post_name) {
			$post_states['home'] = 'Front Page';
		}

		// if signin, signup, signout, set to Authentication
		if (in_array($current_post_name, ['signin', 'signup', 'signout'])) {
			$post_states['authentication'] = 'Auth';
		}

		// success, error, set to 'Message'
		if (in_array($current_post_name, ['success', 'error'])) {
			$post_states['message'] = 'Message';
		}

		// request-access, restricted, set to 'Access Control'
		if (in_array($current_post_name, ['request-access', 'restricted'])) {
			$post_states['access-control'] = 'Access Control';
		}

		// privacy, cookies, terms set to 'Compliance'
		if (in_array($current_post_name, ['privacy', 'cookies', 'terms', 'accessibility'])) {
			$post_states['legal'] = 'Legal';
		}

		// archives
		$post_types = get_post_types(['public' => true], 'objects');

		$archive_paths = [];

		foreach ($post_types as $post_type) {
			$archive_paths[] = $post_type->has_archive;
		}

		$archive_paths = array_filter($archive_paths, 'is_string');

		foreach ($archive_paths as $archive_path) {
			$archive_post_name = str_replace('/', '', $archive_path);

			if ($archive_post_name === $current_post_name) {
				$post_states[$current_post_name] = 'Archive';
			}
		}

		// subscribe, contact set to 'Form'
		if (in_array($current_post_name, ['subscribe', 'contact'])) {
			$post_states['form'] = 'Form';
		}

		// about set to 'info`
		if ('about' === $current_post_name) {
			$post_states['info'] = 'Info';
		}

		return $post_states;
	}
);

add_action(
	'save_post',
	function ($post_id) {

		// string[] Array of post state labels keyed by their state.
		$post_states = get_post_states(get_post($post_id));

		// set post states as terms for the post using taxonomy `post-state`
		$post_state_taxonomy = 'post-state';

		// for each post state, construct a string array used in the set terms

		$terms = [];

		foreach ($post_states as $state => $label) {
			$terms[] = $state;
		}

		// set the terms for the post

		wp_set_object_terms($post_id, $terms, $post_state_taxonomy, false);
	}
);