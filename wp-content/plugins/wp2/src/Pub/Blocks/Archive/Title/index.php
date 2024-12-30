<?php
/**
 * Block Name: Archive Title
 */

$tag = isset($attributes['tag']) ? $attributes['tag'] : 'h1';

$post_id = isset($block['postId']) ? $block['postId'] : null;
$context_post_id = isset($block['context']['postId']) ? $block['context']['postId'] : null;

if ($context_post_id) {
	$post_id = $context_post_id;
}

$archive_title = onthewater_get_archive_title($post_id); 

if ($isEditor) {
	$archive_title = 'Archive Title';
}

$html = '';

if ($tag) {
	$html = '<'. $tag . ' useBlockProps>';
	$html .= '<RichText attribute="prefix" tag="span" placeholder="Prefix" />';
	$html .= $archive_title;
	$html .= '<RichText attribute="suffix" tag="span" placeholder="Suffix" />';
	$html .= '</' . $tag . '>';
}

echo $html;
