<?php

add_filter('mb_settings_pages', function ($settings_pages) {
	$option = 'oddnews_custom_queries';
	$id     = 'custom-queries';
	$settings_pages[] = [
		'menu_title'      => __('Queries', 'oddnews'),
		'id'              => $id,
		'parent'          => 'options-general.php',
		'style'           => 'no-boxes',
		'columns'         => 1,
		'tabs'            => [
			'front-page' => 'Front Page',
		],
		'tab_style'       => 'left',
		'customizer'      => false,
		'customizer_only' => false,
		'network'         => false,
		'option_name'     => $option,
		'icon_url'        => 'dashicons-admin-generic',
	];

	return $settings_pages;
});

add_filter(
	'rwmb_meta_boxes',
	function ($meta_boxes) {

		$settings_page = 'custom-queries';

		$fields = [];

		$meta_boxes[] = [
			'settings_pages' => [$settings_page],
			'fields'         => $fields,
		];

		return $meta_boxes;
	}
);

add_action('mb_settings_page_load', function ($args) {

	$front_page_settings = 'front-page-settings';
	$custom_queries      = 'oddnews_custom_queries';

	if ($args['id'] === $front_page_settings) {

		$featured_posts = rwmb_meta('featured-posts', ['object_type' => 'setting'], $front_page_settings);
		$editors_picks  = rwmb_meta('editors-picks', ['object_type' => 'setting'], $front_page_settings);
		$billboards     = rwmb_meta('billboards', ['object_type' => 'setting'], $front_page_settings);

		$featured_posts = is_array($featured_posts) ? $featured_posts : [];
		$editors_picks  = is_array($editors_picks) ? $editors_picks : [];
		$billboards     = is_array($billboards) ? $billboards : [];

		$new_front_page_data = [
			'front_page' => [
				'featured' => [
					'posts'  => $featured_posts,
					'editors_picks' => $editors_picks,
					'billboards' => $billboards,
				]
			],
		];

		$prev_custom_queries = get_option($custom_queries, []);

		$new_custom_queries = array_merge($prev_custom_queries, $new_front_page_data);

		update_option($custom_queries, $new_custom_queries);

		do_action('qm/debug', get_option($custom_queries));
	}
}, 20);


function oddnews_get_query_item($query, $index)
{

	/**
	 * Query Item
	 * Given the query name, we can look up the find item in the option `oddnews_custom_queries`
	 * a switch statement will be used to detemein where in the option the value is stored
	 * the index value is the exact location of the item to return
	 * we are only returnining an id.
	 */

	$option = 'oddnews_custom_queries';

	$option_value = get_option($option);

	$item = [];

	/**
	 * When front_page_featured_posts is the query name then
	 * we will return front_page -> featured -> posts -> $index
	 * it will be different for each query name
	 */

	switch ($query) {
		case 'front_page_featured_posts':
			$item_id = match ($query) {
				'front_page_featured_posts' => isset($option_value['front_page']['featured']['posts'][$index]) ? $option_value['front_page']['featured']['posts'][$index] : 0,
				default => 0,
			};
			$item = [
				'type' => 'post',
				'id'   => $item_id,
				'query' => $query,
				'index' => $index,
				'collections' => [
					'featured',
				],
				'conditions' => [
					'front_page',
				],
			];
			break;
		default:
			$item = [
				'type' => 'post',
				'id'   => 0,
				'query' => $query,
				'index' => $index,
				'collections' => [],
				'conditions' => [],
			];
			break;
	}

	return $item;
}

function oddnews_get_post_template_item_data($template_name, $post_id)
{

	$post_title = oddnews_get_the_title($post_id);
	$post_permalink = get_permalink($post_id);
	$post_thumbnail = oddnews_get_the_post_thumbnail($post_id);
	$post_excerpt = oddnews_get_the_excerpt($post_id);
	$post_term = oddnews_get_the_term($post_id);
	$post_byline = oddnews_get_the_byline($post_id);
	$post_dateline = oddnews_get_the_dates($post_id, false, true);

	$data = [];

	switch ($template_name) {
		case 'default':
			$data = [
				'title' => $post_title,
				'excerpt' => $post_excerpt,
				'permalink' => $post_permalink,
				'thumbnail' => $post_thumbnail,
				'term' => $post_term,
				'byline' => $post_byline,
				'dateline' => $post_dateline
			];
			break;
	}

	return $data;
}

function oddnews_get_the_title($post_id)
{
	$title = '<div class="wp-block-post-title">' . get_the_title($post_id) . '</div>';

	return $title;
}

function oddnews_get_the_post_thumbnail($post_id)
{
	$thumbnail = get_the_post_thumbnail($post_id, 'large', ['class' => 'object-fit-cover']);

	$thumbnail = '<figure class="wp-block-post-featured-image">' . $thumbnail . '</figure>';

	return $thumbnail;
}

function oddnews_get_the_excerpt($post_id)
{
	$excerpt = get_the_excerpt($post_id);

	$excerpt = '<div class="wp-block-post-excerpt">' . $excerpt . '</div>';

	return $excerpt;
}

function oddnews_get_the_term($post_id)
{

	$post_type = get_post_type($post_id);


	$term = null;

	switch ($post_type) {
		case 'post':

			if (is_category()) {
				$term = null;
			} else {
				$term = get_the_category($post_id);
			}

			break;

		case ('article' || 'slideshow'):

			if (is_tax('topic')) {
				$term = null;
			} else {
				$terms = get_the_terms($post_id, 'topic');
				if ($terms) {
					$term = $terms[0];
				}
			}

			break;
	}

	$term = sprintf(
		'<div class="wp-block-oddnews-post-term">
				<a href="%s">
					%s
				</a>
			</div>',
		esc_url(get_term_link($term)),
		$term->name
	);

	return $term;
}

function oddnews_get_the_byline($post_id)
{
	$byline = '';

	$coauthors = get_coauthors($post_id);

	$byline_item_count = 0;

	if ($coauthors) {
		foreach ($coauthors as $author) {

			if (is_author($author->ID)) {
				continue;
			}

			$byline_item_count++;

			$byline_items = sprintf(
				'<li class="wp-block-oddnews-post-coauthor"><a href="%s" title="Posts by %s">%s</a></li>',
				get_author_posts_url($author->ID),
				$author->display_name,
				$author->display_name
			);
		}
	}

	$byline = sprintf(
		'<div class="wp-block-oddnews-post-byline"><ul class="wp-block-oddnews-post-coauthors">%s</ul></div>',
		$byline_items
	);

	// if there are no byline items, return an empty string

	if ($byline_item_count === 0) {
		$byline = '';
	}

	return $byline;
}

function oddnews_get_the_dates($post_id, $get_modified = false, $include_wrapper = true)
{
	$published_formatted = get_the_date('F j, Y', $post_id);
	$published_datetime  = get_the_date('c', $post_id);

	$published_dateline = sprintf(
		'<p class="wp-block-post-date">
				<span class="wp-block-post-date-prefix">Published</span><time datetime="%s">%s</time>
		</p>',
		esc_html($published_datetime),
		esc_html($published_formatted)
	);

	if ($get_modified) {
		$modified_formatted  = get_the_modified_date('F j, Y', $post_id);
		$modified_datetime   = get_the_modified_date('c', $post_id);


		$modified_dateline = sprintf(
			'<p class="wp-block-post-date %s">
				<span class="wp-block-post-date-prefix">Updated</span> <time datetime="%s">%s</time>
		</p>',
			$get_modified ? 'wp-block-post-date__modified-date' : 'wp-block-post-date__modified-date visually-hidden',
			esc_html($modified_datetime),
			esc_html($modified_formatted)
		);
	}

	$dateline = sprintf(
		'<div class="wp-block-oddnews-post-dates">
			%s
			%s
		</div>',
		$published_dateline,
		$modified_dateline ?? ''
	);

	if (!$include_wrapper) {
		$dateline = '<div class="wp-block-oddnews-dateline">' . $published_formatted . '</div>';
	}

	return $dateline;
}
