<?php
$post_type = isset($block['postType']) ? $block['postType'] : '';

$post = get_post($post_id);

$types_dir = __dir__ . '/types';

$type = '';

switch ($post_type) {
    case FREEWP_PREFIX . 'activity':
        $type = 'activity';
        break;
    case FREEWP_PREFIX . 'news':
        $type = 'article-news';
        break;
    default:
        break;
}

include __dir__ . '/types/' . $type . '.php';
