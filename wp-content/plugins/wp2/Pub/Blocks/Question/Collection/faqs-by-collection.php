<?php
$attributes = isset($attributes) ? $attributes : null;

$align = isset($attributes['align']) ? $attributes['align'] : '';

$faq_collections = get_terms([
	'taxonomy' => 'faq_collection',
	'hide_empty' => true,
	'meta_key' => 'position',
	'orderby' => 'meta_value_num',
	'order' => 'ASC',
]);

?>



<div class="faq-collections <?php echo esc_attr('align' . $align); ?>">

	<?php foreach ($faq_collections as $faq_collection) : ?>

		<?php
		$collection_name = $faq_collection->name;
		$collection_slug = $faq_collection->slug;
		$collection_description = $faq_collection->description;
		$collection_slogan = get_term_meta($faq_collection->term_id, 'slogan', true);

		$faqs = get_posts([
			'post_type' => 'faq',
			'numberposts' => 6,
			'meta_key' => 'position',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'tax_query' => [
				[
					'taxonomy' => 'faq_collection',
					'field' => 'slug',
					'terms' => $collection_slug
				]
			]
		]);
		?>

		<div class="faq-collection">

			<div class="faq-collection-header wp-block-group alignfull has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
				<div class="wp-block-group is-layout-flow wp-block-group-is-layout-flow">
					<?php if ($collection_slogan) : ?>
						<p class="has-text-align-center has-primary-color has-text-color has-small-font-size"><?php echo esc_html($collection_slogan); ?></p>
					<?php endif; ?>
					<h2 class="wp-block-heading has-text-align-center" id=""><?php echo esc_html($collection_name); ?></h2>
					<?php if ($collection_description) : ?>
						<p class="has-text-align-center"><?php echo esc_html($collection_description); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<div class="faq-collection-main">

				<div class="faqs">

					<div class="faq-items">

					

						<?php foreach ($faqs as $faq) : ?>

							<?php
							$faq_title = $faq->post_title;
							$faq_excerpt = $faq->post_excerpt;
							$faq_excerpt = do_shortcode($faq_excerpt);
							$faq_permalink = get_permalink($faq->ID);
							$faq_slug = $faq->post_name;
							$faq_position = get_post_meta($faq->ID, 'position', true);
							?>
							<div class="faq-item <?php echo 'position-' . esc_attr($faq_position); ?>">
								<h3 class="faq-heading" id="<?php echo $faq_slug; ?>">
									<?php echo esc_html($faq_title); ?>
								</h3>
								<p class="faq-excerpt">
									<?php echo esc_html($faq_excerpt); ?>
								</p>
							</div>

						<?php endforeach; ?>

					</div>

				</div>

			</div>

		</div>

	<?php endforeach; ?>

	<?php
	$all_faqs_with_any_collection = get_posts([
		'post_type' => 'faq',
		'posts_per_page' => -1,
		'tax_query' => [
			[
				'taxonomy' => 'faq_collection',
				'operator' => 'EXISTS'
			]
		]
	]);
	?>

	<?php if ($all_faqs_with_any_collection) : ?>

		<?php
		$faq_page_schema = '';
		$faq_page_schema .= '<script id="faq-by-collection-schema" type="application/ld+json">';
		$faq_page_schema .= '{';
		$faq_page_schema .= '"@context": "https://schema.org",';
		$faq_page_schema .= '"@type": "FAQPage",';
		$faq_page_schema .= '"mainEntity": [';

		$last_faq = end($all_faqs_with_any_collection);

		foreach ($all_faqs_with_any_collection as $faq) {
			$faq_title = $faq->post_title;
			$faq_excerpt = $faq->post_excerpt;
			$faq_excerpt = do_shortcode($faq_excerpt);
			$faq_permalink = get_permalink($faq->ID);
			$faq_slug = $faq->post_name;
			$faq_position = get_post_meta($faq->ID, 'position', true);
			$faq_page_schema .= '{';
			$faq_page_schema .= '"@type": "Question",';
			$faq_page_schema .= '"name": "' . esc_html($faq_title) . '",';
			$faq_page_schema .= '"acceptedAnswer": {';
			$faq_page_schema .= '"@type": "Answer",';
			$faq_page_schema .= '"text": "' . esc_html($faq_excerpt) . '"';
			$faq_page_schema .= '}';
			$faq_page_schema .= '}';
			if ($faq !== $last_faq) {
				$faq_page_schema .= ',';
			}
		}
		$faq_page_schema .= ']';
		$faq_page_schema .= '}';
		$faq_page_schema .= '</script>';
		?>

		<?php echo $faq_page_schema; ?>

	<?php endif; ?>

</div>