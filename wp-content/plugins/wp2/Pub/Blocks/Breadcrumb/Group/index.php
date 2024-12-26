<?php
$post_id = isset($block['postId']) ? $block['postId'] : null;
$context_post_id = isset($block['context']['postId']) ? $block['context']['postId'] : null;

if ($context_post_id) {
	$post_id = $context_post_id;
}

$taxonomy = null;

if ($post_id) {
	$taxonomy = get_post_type($post_id) === 'post' ? 'category' : 'topic';
}

$breadcrumbs = onthewater_get_the_breadcrumbs($taxonomy);

?>

<div useBlockProps>
	<?php if (!$isEditor) : ?>
		<?php echo $breadcrumbs; ?>
	<?php else : ?>
		<nav class="breadcrumb" aria-label="Breadcrumbs">
			<a href="javascript:void(0)" class="breadcrumb-item">Home</a>
			<a href="javascript:void(0)" class="breadcrumb-item">Level</a>
			<a href="javascript:void(0)" class="breadcrumb-item">Level</a>
			<span class="breadcrumb-item" aria-current="page">Current</span>
		</nav>
	<?php endif; ?>
</div>