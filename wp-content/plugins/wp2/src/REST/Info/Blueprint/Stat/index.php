<?php
/*
 * Name: Instawp Template Items Count
 */
$block   = isset($block) ? $block : null;
$post_id = isset($block['context']['postId']) ? $block['context']['postId'] : null;
$page    = get_post($post_id);

$template_data_key = get_post_meta($post_id, 'newsplicity_template_data_name', true);

$count = count(get_children([
	'post_parent' => $post_id,
	'post_type' => 'page',
	'post_status' => 'publish'
]));

if (!$count) {
	return;
}

if ($count === 0) {

	$template_data_json = get_option('newsplicity_template_data');

	$template_data = json_decode($template_data_json);

	$items = $template_data->$template_data_key;

	$count = count($items);
}

?>

<div useBlockProps>

	<RichText attribute="prefix" />
	<?php echo $count; ?>
	<RichText attribute="suffix" />

</div>