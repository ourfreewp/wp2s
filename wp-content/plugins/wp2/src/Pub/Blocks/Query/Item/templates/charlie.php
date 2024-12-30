<?php

/**
 * Name: Charlie
 */

$item = isset($item) ? $item : [];
$id   = isset($item['id']) ? $item['id'] : '';
$type = isset($item['type']) ? $item['type'] : '';

if ($type !== 'post') {
	return;
}

$title     = get_the_title($id);
$excerpt = get_the_excerpt($id);
$excerpt = substr($excerpt, 0, 80);
$excerpt = substr($excerpt, 0, strrpos($excerpt, ' '));
$permalink = get_the_permalink($id);
$dateline  = get_the_date('F j, Y', $id);

?>

<div <?php post_class(['wp-block-post position-relative'], $item_id); ?>>

	<div class="wp-block-post-title font-heading">
		<?php echo esc_html($title); ?>
	</div>

	<div class="wp-block-post-excerpt has-small-font-size mb-0">
		<?php echo esc_html($excerpt); ?>
	</div>

	<div class="wp-block-post-dateline has-small-font-size font-meta text-gray fw-bold">
		<?php echo esc_html($dateline); ?>
	</div>

	<?php if (!$isEditor) : ?>
		<a class="stretched-link" href="<?php echo esc_url($permalink); ?>">
			<p class="screen-reader-text"> Continue Reading <span><?php echo esc_html($title); ?></span></p>
		</a>
	<?php endif; ?>

</div>