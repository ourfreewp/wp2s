<?php
/*
Plugin Name: Slideshow Feed Content Filter
Description: Adds a custom filter to the RSS feed for the slideshow post type.
*/

add_action('rss2_item', function () {
    global $post;

    // Check if this is the 'slideshow' post type
    if ($post->post_type !== 'slideshow') {
        return;
    }

    // Get post slides data
    $slide_data = rwmb_get_value('slideshow-slides', [], $post->ID);

    if (empty($slide_data)) {
        return;
    }

    $slides = '';
    $prefix = 'slideshow-';

    // Loop through the slideshow slides and construct media tags for RSS feed
    foreach ($slide_data as $slide) {
        $slide_title = isset($slide[$prefix . 'slide-title']) ? $slide[$prefix . 'slide-title'] : '';
        $slide_content = isset($slide[$prefix . 'slide-content']) ? $slide[$prefix . 'slide-content'] : '';
        $slide_image = isset($slide[$prefix . 'slide-image'][0]) ? $slide[$prefix . 'slide-image'][0] : '';
        $slide_image_url = wp_get_attachment_image_src($slide_image, 'full')[0] ?? '';
        $slide_image_credit = isset($slide[$prefix . 'slide-image-credit']) ? $slide[$prefix . 'slide-image-credit'] : '';

        // Construct media content block with proper indentation and line breaks
        $slide_markup = '        <media:content url="' . esc_url($slide_image_url) . '" medium="image" type="image/jpeg">' . PHP_EOL;

        if (!empty($slide_image_credit)) {
            $slide_markup .= '            <media:credit><![CDATA[' . esc_html($slide_image_credit) . ']]></media:credit>' . PHP_EOL;
        }

        if (!empty($slide_title)) {
            $slide_markup .= '            <media:title><![CDATA[' . esc_html($slide_title) . ']]></media:title>' . PHP_EOL;
        }

        if (!empty($slide_content)) {
            $slide_markup .= '            <media:description type="html"><![CDATA[' . wp_kses_post(apply_filters('the_content', $slide_content)) . ']]></media:description>' . PHP_EOL;
        }

        $slide_markup .= '        </media:content>' . PHP_EOL;

        $slides .= $slide_markup;
    }

    // Output the slide media content in the RSS feed
    echo $slides;
});


// Filter the post content in the RSS feed specifically for the slideshow post type
add_filter('the_content_feed', function ($content, $post) {
    if ($post->post_type === 'slideshow') {
        $slides_data = rwmb_get_value('slideshow-slides', [], $post->ID);

        if (!empty($slides_data)) {
            $content .= '<div class="slideshow-slide">' . PHP_EOL;

            // Loop through the slides and append them to the content with proper formatting
            foreach ($slides_data as $slide) {
                $slide_title = isset($slide['slideshow-slide-title']) ? $slide['slideshow-slide-title'] : '';
                $slide_content = isset($slide['slideshow-slide-content']) ? $slide['slideshow-slide-content'] : '';
                $slide_image = isset($slide['slideshow-slide-image'][0]) ? $slide['slideshow-slide-image'][0] : '';
                $slide_image_url = wp_get_attachment_image_src($slide['slideshow-slide-image'][0], 'full')[0] ?? '';

                // Append each slide to the content with formatting
                $content .= '    <h3>' . esc_html($slide_title) . '</h3>' . PHP_EOL;
                $content .= '    <img src="' . esc_url($slide_image_url) . '" alt="' . esc_attr($slide_title) . '">' . PHP_EOL;
                $content .= '    <p>' . esc_html($slide_content) . '</p>' . PHP_EOL;
            }

            $content .= '</div>' . PHP_EOL;
        }
    }

    return $content;
}, 10, 2);