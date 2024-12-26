<?php
$template_name = isset($attributes['template']) ? $attributes['template'] : 'default';
$post_id       = isset($block['context']['postId']) ? $block['context']['postId'] : null;
$item          = onthewater_get_post_template_item_data($template_name, $post_id);
$item_template = __DIR__ . '/templates/' . $template_name . '.php';
?>

<article useBlockProps class="is-template-<?php echo esc_attr($template_name); ?>">

	<?php include $item_template; ?>

</article>