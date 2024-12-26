<?php

$attributes = isset($attributes) ? $attributes : null;

$align = isset($attributes['align']) ? $attributes['align'] : '';

$sticky_faqs = get_posts([
	'post_type'      => 'faq',
	'posts_per_page' => 6,
	'meta_key' => 'position',
	'orderby' => 'meta_value_num',
	'order' => 'ASC',
	'meta_query' => [
		[
			'key' => 'sticky',
			'value' => '1',
			'compare' => '='
		]
	]
]);

?>

<?php if ($sticky_faqs) : ?>

	<div class="faqs faqs--featured <?php echo esc_attr('align' . $align); ?>">

		<div class="faq-items">

			<?php foreach ($sticky_faqs as $faq) : ?>

				<?php
				$faq_title = $faq->post_title;
				$faq_excerpt = $faq->post_excerpt;
				$faq_excerpt = do_shortcode($faq_excerpt);
				$faq_permalink = get_permalink($faq->ID);
				$faq_slug = $faq->post_name;
				$faq_position = get_post_meta($faq->ID, 'position', true);
				?>
				<div class="faq-item <?php echo 'position-' . esc_attr($faq_position); ?>">
					<div class="faq-heading" id="<?php echo $faq_slug; ?>">
						<?php echo esc_html($faq_title); ?>
					</div>
					<p class="faq-excerpt">
						<?php echo esc_html($faq_excerpt); ?>
					</p>
				</div>

			<?php endforeach; ?>

		</div>

		<script id="featured-faq-schema" type="application/ld+json">
			{
				"@context": "https://schema.org",
				"@type": "FAQPage",
				"mainEntity": [
					<?php $last_faq = end($sticky_faqs);
					foreach ($sticky_faqs as $faq) : ?> {
							"@type": "Question",
							"name": "<?php echo esc_html($faq->post_title); ?>",
							"acceptedAnswer": {
								"@type": "Answer",
								"text": "<?php echo esc_html($faq->post_excerpt); ?>"
							}
						}
						<?php if ($faq !== $last_faq) {
							echo ',';
						} ?>
					<?php endforeach; ?>
				]
			}
		</script>

	</div>

<?php endif; ?>