<?php

add_action('save_post', function ($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_revision($post_id)) {
        return;
    }

    $content = get_post_field('post_content', $post_id);

    if (empty($content)) {
        return;
    }

    $word_count = str_word_count(strip_tags($content));

    $readingtime = ceil($word_count / 225);

    update_post_meta($post_id, 'post_time_to_read', $readingtime);
	
});
