<?php

add_feed('rss-msn', function () {

	header('Content-Type: application/rss+xml');

	$rss = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
	$rss .= '<rss xmlns:atom="http://www.w3.org/2005/Atom" ';
	$rss .= 'xmlns:media="http://search.yahoo.com/mrss/" ';
	$rss .= 'xmlns:mi="http://schemas.ingestion.microsoft.com/common/" ';
	$rss .= 'xmlns:dc="http://purl.org/dc/elements/1.1/" ';
	$rss .= 'xmlns:content="http://purl.org/rss/1.0/modules/content/" ';
	$rss .= 'xmlns:dcterms="http://purl.org/dc/terms/" ';
	$rss .= 'version="2.0">' . "\n";
	$rss .= '<channel>' . "\n";
	$rss .= '<atom:link href="' . home_url('/rss-msn/') . '" rel="self" type="application/rss+xml"></atom:link>' . "\n";
	$rss .= '<title>' . get_bloginfo('name') . '</title>' . "\n";
	$rss .= '<description>' . get_bloginfo('description') . '</description>' . "\n";
	$rss .= '<language>en-us</language>' . "\n";
	$rss .= '<link>' . get_bloginfo('url') . '</link>' . "\n";
	$rss .= '%s';
	$rss .= '</channel>';
	$rss .= '</rss>';

	$posts = get_posts([
		'posts_per_page' => 10,
		'post_type' => ['article'],
		'post_status' => 'publish',
	]);

	$items = '';

	foreach ($posts as $post) {

		$permalink = get_permalink($post->ID);
		$post_title = esc_html($post->post_title);
		$post_date = date('D, d M Y H:i:s O', strtotime($post->post_date));
		$author = esc_html(get_the_author_meta('display_name', $post->post_author));
		$tags = esc_html(implode(', ', wp_get_post_tags($post->ID, ['fields' => 'names'])));
		$categories = esc_html(implode(', ', wp_get_post_categories($post->ID, ['fields' => 'names'])));
		$excerpt = wp_kses_post(apply_filters('the_content', $post->post_excerpt));
		$content = wp_kses_post(apply_filters('the_content', $post->post_content));

		$item = '<item>';

			if (!empty($permalink)) {
				$item .= '<guid isPermaLink="false">' . $permalink . '</guid>';
			}

			if (!empty($post_title)) {
				$item .= '<title><![CDATA[' . $post_title . ']]></title>';
			}

			if (!empty($permalink)) {
				$item .= '<link>' . $permalink . '</link>';
			}

			if (!empty($post_date)) {
				$item .= '<pubDate>' . $post_date . '</pubDate>';
			}

			if (!empty($author)) {
				$item .= '<dc:creator>' . $author . '</dc:creator>';
			}

			if (!empty($categories)) {
				$item .= '<category>' . $categories . '</category>';
			}

			if (!empty($tags)) {
				$item .= '<media:keywords>' . trim($tags) . '</media:keywords>';
			}

			if (!empty($excerpt)) {
				$item .= '<description><![CDATA[' . trim($excerpt) . ']]></description>';
			}

			if (!empty($content)) {
				$item .= '<content:encoded><![CDATA[' . trim($content) . ']]></content:encoded>';
			}

		$item .= '</item>';

		$items .= $item . "\n";

	}

	echo trim(sprintf($rss, $items));

	exit;

});