<?php
$block   = isset($block) ? $block : [];
$postId  = isset($block['postId']) ? $block['postId'] : 0;
$inherit = isset($attributes['inherit']) ? $attributes['inherit'] : [];
$option  = isset($attributes['option']) ? $attributes['option'] : [];

$template_name = null;
$inherit_form  = null;

if ($inherit) {
	$post      = get_post($postId);
	$post_slug = $post->post_name;
	$template_name  = $post_slug;
	$inherit_form = get_post_meta($postId, 'user_page_form_id', true);
} else {
	$template_name = $option;
}

$template_path    = __DIR__ . '/templates/' . $template_name . '.php';
$placeholder_path = __DIR__ . '/placeholders/' . $template_name . '.php';
$missing_placeholder_path = __DIR__ . '/placeholders/missing.php';

if (!file_exists($template_path) || !file_exists($placeholder_path)) {
	$placeholder_path = $missing_placeholder_path;
}

?>

<div useBlockProps>
	<?php if (file_exists($template_path)) : ?>
		<?php include $template_path; ?>
	<?php else : ?>
		<?php include $missing_placeholder_path; ?>
	<?php endif; ?>
</div>