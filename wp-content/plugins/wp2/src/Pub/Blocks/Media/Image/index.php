<?php
$attributes = isset($attributes) ? $attributes : [];
$image      = isset($attributes['image']) ? $attributes['image'] : [];
?>

<MediaPlaceholder attribute="image" allowedTypes="<?php echo esc_attr(wp_json_encode(['image'])); ?>" />

<?php if (!empty($image)) : ?>
	<?php
	$image_id = isset($image['ID']) ? $image['ID'] : null;

	if (empty($image_id)) {
		return;
	}

	$requested_image_size = isset($attributes['size']) ? $attributes['size'] : 'thumbnail';

	$image_attachment = get_post($image_id);
	$image_attachment_title = $image_attachment->post_title ?? '';
	$image_attachment_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?? '';
	$image_attachment_caption = wp_get_attachment_caption($image_id) ?? '';
	$image_attachment_byline = get_post_meta($image_id, 'byline', true) ?? '';
	$image_attachment_src_set = wp_get_attachment_image_srcset($image_id, $requested_image_size) ?? '';

	$block_level_image_title = isset($attributes['title']) ? $attributes['title'] : $image_attachment_title;
	$block_level_image_alt = isset($attributes['alt']) ? $attributes['alt'] : $image_attachment_alt;
	$block_level_image_caption = isset($attributes['caption']) ? $attributes['caption'] : $image_attachment_caption;
	$block_level_image_byline = isset($attributes['byline']) ? $attributes['byline'] : $image_attachment_byline;

	$styles = '';

	$max_width = isset($attributes['max-width']) ? $attributes['max-width'] : null;
	$styles .= $max_width ? 'max-width: ' . $max_width . ';' : 'auto';

	$max_height = isset($attributes['max-height']) ? $attributes['max-height'] : null;
	$styles .= $max_height ? 'max-height: ' . $max_height . ';' : 'auto';

	$image_html = wp_get_attachment_image($image_id, $requested_image_size, false, [
		'title' => $block_level_image_title ?? '',
		'alt' => $block_level_image_alt ?? '',
		'srcset' => $image_attachment_src_set ?? '',
		'style' => $styles,
	]);


	$image_caption = $block_level_image_caption ? $block_level_image_caption : $image_attachment_caption;

	$image_byline = $block_level_image_byline ? $block_level_image_byline : $image_attachment_byline;

	?>

	<figure useBlockProps class="wp-block-image">

		<?php echo wp_kses($image_html, 'post'); ?>

		<?php if (!empty($image_caption) || !empty($image_byline)) : ?>

			<figcaption class="wp-element-caption">

				<?php if (!empty($image_caption)) : ?>
					<span><?php echo esc_html($image_caption); ?></span>
				<?php endif; ?>

				<?php if (!empty($image_byline)) : ?>
					<span><?php echo esc_html($image_byline); ?></span>
				<?php endif; ?>


			</figcaption>

		<?php endif; ?>

	</figure>

<?php endif; ?>