<?php

/**
 * Name: Bravo
 */

$item = isset($item) ? $item : [];
$id   = isset($item['id']) ? $item['id'] : '';
$type = isset($item['type']) ? $item['type'] : '';

if ($type !== 'post') {
	return;
}

$thumbnail = get_the_post_thumbnail($id, 'medium', ['class' => 'object-fit-cover w-100 h-100']);
$title     = get_the_title($id);
$term	  = onthewater_get_the_term($id);
$excerpt   = get_the_excerpt($id);
$permalink = get_the_permalink($id);
$dateline  = get_the_date('F j, Y', $id);

?>

<div <?php post_class(['wp-block-post position-relative'], $item_id); ?>>

	<?php if ($thumbnail) : ?>
		<div class="wp-block-post-thumbnail ratio ratio-1x1">
			<?php echo $thumbnail; ?>
		</div>
	<?php endif; ?>

	<div class="font-meta d-flex has-small-font-size text-primary fw-bold mt-1 text-uppercase">
		<?php echo $term->name; ?>
	</div>


	<div class="wp-block-post-title font-heading">
		<?php echo esc_html($title); ?>
	</div>

	<div class="wp-block-post-dateline has-small-font-size font-meta text-gray fw-bold">
		<?php echo esc_html($dateline); ?>
	</div>

	<div class="wp-block-post-excerpt has-small-font-size my-0">
		<?php echo esc_html($excerpt); ?>
	</div>

	<?php if (!$isEditor) : ?>
		<a class="stretched-link" href="<?php echo esc_url($permalink); ?>">
			<p class="screen-reader-text"> Continue Reading <span><?php echo esc_html($title); ?></span></p>
		</a>
	<?php endif; ?>

</div>