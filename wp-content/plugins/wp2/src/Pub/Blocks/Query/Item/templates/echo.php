<?php

/**
 * Name: Echo
 */

$item = isset($item) ? $item : [];
$id   = isset($item['id']) ? $item['id'] : '';
$type = isset($item['type']) ? $item['type'] : '';

if ($type !== 'post') {
	return;
}

$thumbnail = get_the_post_thumbnail($id, 'full', ['class' => 'object-fit-cover']);
$title     = get_the_title($id);
$term 	   = onthewater_get_the_term($id);
$excerpt   = get_the_excerpt($id);
$permalink = get_the_permalink($id);
$byline    = onthewater_get_the_byline($id);
$dateline  = onthewater_get_the_dateline($id);

?>

<div <?php post_class(['wp-block-post'], $item_id); ?>>

	<div class="position-relative">

		<?php if ($thumbnail) : ?>
			<div class="wp-block-post-thumbnail">
				<?php echo $thumbnail; ?>
			</div>
		<?php endif; ?>

		<div class="d-flex flex-row">
			<div class="font-meta d-flex justify-content-lg-center has-small-font-size text-primary fw-bold mt-1 text-uppercase">
				<?php echo $term->name; ?>
			</div>
			<div class="font-meta d-flex justify-content-lg-center has-small-font-size text-primary fw-bold mt-1 text-uppercase">
				<?php echo $dateline; ?>
			</div>
		</div>

		<div class="wp-block-post-title font-heading text-lg-center" style="font-size: clamp(1.9rem, 8vi + -0.05rem, 2rem); line-height: 115%;">
			<?php echo esc_html($title); ?>
		</div>

		<div class="wp-block-post-excerpt font-excerpt text-lg-center mb-0 has-small-font-size">
			<?php echo esc_html($excerpt); ?>
		</div>

		<?php if (!$isEditor) : ?>
			<a class="stretched-link" href="<?php echo esc_url($permalink); ?>">
				<p class="screen-reader-text"> Continue Reading <span><?php echo esc_html($title); ?></span></p>
			</a>
		<?php endif; ?>

	</div>

	<div class="font-meta d-flex justify-content-lg-center has-small-font-size text-gray fw-bold">
		<?php echo wp_kses($byline, 'post'); ?>
	</div>

</div>