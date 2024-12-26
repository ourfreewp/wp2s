<?php
$tag                = isset($attributes['tag']) ? $attributes['tag'] : 'div';
$item_attributes    = isset($attributes) ? $attributes : [];
$item_template_name = isset($attributes['template']) ? $attributes['template'] : 'undefined';
if (empty($item_template_name)) {
	$item_template_name = 'undefined';
}
$item_template_path  = __DIR__ . '/templates/' . $item_template_name . '.php';

$query     = isset($attributes['query']) ? $attributes['query'] : [];
$index     = isset($block['index']) ? $block['index'] : '';
$item      = onthewater_get_query_item($query, $index);
$html_tag = '<' . $tag . ' useBlockProps class="is-template-' . $item_template_name . '">';

if (file_exists($item_template_path)) {
	include $item_template_path;
}

echo '</' . $tag . '>';
