<?php

add_action('pre_get_posts', function ($query) {

	if (!is_admin() && $query->is_main_query()) {

		// If the user is not logged in, exclude private posts
		if (!is_user_logged_in()) {
			$query->set('post_status', 'publish');
		}

		// Ensure that private posts are excluded in RSS feeds
		if ($query->is_feed()) {
			$query->set('post_status', 'publish');
		}

		// Ensure that the number of posts per page matches the feed settings
		$posts_per_page = get_option('posts_per_page', 10); // Default to 10 if the option is not set
		$query->set('posts_per_page', $posts_per_page);

		// if current user is not an administrator
		if (!current_user_can('manage_options')) {
			$exclude_everywhere = [
				'taxonomy' => 'attribute',
				'field'    => 'slug',
				'terms'    => 'exclude-everywhere',
				'operator' => 'NOT IN',
			];
			$query->set('tax_query', [$exclude_everywhere]);
		}

		// Handle different query contexts and post types
		if ($query->is_front_page() || $query->is_home()) {
			$query->set('post_type', ['article', 'slideshow']);
		}

		if ($query->is_search()) {
			$query->set('post_type', ['article', 'slideshow']);
		}

		if ($query->is_author()) {
			$query->set('post_type', ['article', 'slideshow']);
		}

		if ($query->is_category()) {
			$query->set('post_type', ['article', 'slideshow']);
		}

		if ($query->is_tag()) {
			$query->set('post_type', ['article', 'slideshow']);
		}

		if ($query->is_tax('topic')) {
			$query->set('post_type', ['article', 'slideshow']);
		}

		if ($query->is_post_type_archive('archive-editors-pick')) {
			$query->set('post_type', ['archive-editors-pick', 'article', 'slideshow']);
		}
	}
});
