<?php

$parent_id = isset($block['context']['postId']) ? $block['context']['postId'] : '';
$parent_type = isset($block['context']['postType']) ? $block['context']['postType'] : '';
$parent_post = get_post($parent_id);

$post_id = isset($block['postId']) ? $block['postId'] : '';
$post_type = isset($block['postType']) ? $block['postType'] : '';
$post = get_post($post_id);

$types = __dir__ . '/types';

switch ($post_type) {
    case 'activity':
        require_once $types . '/activity.php';
        break;
    case 'news':
        require_once $types . '/article-news.php';
        break;
    default:
        break;
}