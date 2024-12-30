<?php
add_feed('rss-msn-slideshows', function () {

	$feed_name = 'rss-msn-slideshows';

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
	$rss .= '<title>' . get_bloginfo('name') . '</title>' . "\n";
	$rss .= '<atom:link href="' . home_url('/' . $feed_name . '/') . '" rel="self" type="application/rss+xml"></atom:link>' . "\n";
	$rss .= '<description>' . get_bloginfo('description') . '</description>' . "\n";
	$rss .= '<language>en-us</language>' . "\n";
	$rss .= '<link>' . get_bloginfo('url') . '</link>' . "\n";
	$rss .= '%s' . "\n";
	$rss .= '</channel>' . "\n";
	$rss .= '</rss>' . "\n";

	$posts = get_posts([
		'posts_per_page' => 10,
		'post_type' => ['slideshow'],
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

		$slide_data = rwmb_get_value('slideshow-slides', [], $post->ID);

		$slides = '';

		$prefix = 'slideshow-';

		foreach ($slide_data as $slide) {

			$slide_title = isset($slide[$prefix . 'slide-title']) ? $slide[$prefix . 'slide-title'] : '';

			$slide_content = isset($slide[$prefix . 'slide-content']) ? $slide[$prefix . 'slide-content'] : '';

			$slide_image = isset($slide[$prefix . 'slide-image'][0]) ? $slide[$prefix . 'slide-image'][0] : '';

			$slide_image_url = wp_get_attachment_image_src($slide_image, 'full')[0];

			$slide_image_credit = isset($slide[$prefix . 'slide-image-credit']) ? $slide[$prefix . 'slide-image-credit'] : '';

			$slide = '<media:content url="' . $slide_image_url . '" medium="image" type="image/jpeg">' . "\n";

			if (!empty($slide_image_credit)) {
				$slide .= '<media:credit><![CDATA[' . $slide_image_credit . ']]></media:credit>' . "\n";
			}

			if (!empty($slide_title)) {
				$slide .= '<media:title><![CDATA[' . $slide_title . ']]></media:title>' . "\n";
			}

			if (!empty($slide_content)) {
				$slide .= '<media:description type="html"><![CDATA[' . wp_kses_post(apply_filters('the_content', $slide_content )) . ']]></media:description>' . "\n";
			}

			$slide .= '</media:content>' . "\n";

			$slides .= $slide . "\n";

		}

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

		if (!empty($slides)) {
			$item .= $slides;
		}

		$item .= '</item>';

		$items .= $item . "\n";

	}

	echo trim(sprintf($rss, $items));

	exit;

});