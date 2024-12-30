<?php

$attributes = isset($attributes) ? $attributes : [];

$item_selected = isset($attributes['item']) ? $attributes['item'] : '';

$items = [];

switch ($item_selected) {
	case 'pages':
		$items = newsplicity_template_items_pages();
		break;
	case 'plugins':
		$items = newsplicity_template_items_plugins();
		break;
	case 'php-extensions':
		$items = newsplicity_template_items_php_extensions();
		break;
	case 'block-types':
		$items = newsplicity_template_items_block_types();
		break;
	case 'post-types':
		$items = newsplicity_template_items_post_types();
		break;
	case 'taxonomies':
		$items = newsplicity_template_items_taxonomies();
		break;
	case 'themes':
		$items = newsplicity_template_items_themes();
		break;
	case 'shortcodes':
		$items = newsplicity_template_items_shortcodes();
		break;
}

if (empty($items)) {
	echo 'No items found.';
	return;
}

foreach ($items as $item) {
	if (!isset($item['category'])) {
		$item['category'] = 'Other';
	}
}

// drop any items where is_hidden is true

$hidden_items = array_filter($items, function ($item) {
	return $item['is_hidden'];
});

$items = array_filter($items, function ($item) {
	return !$item['is_hidden'];
});

$item_categories = [];

foreach ($items as $item) {
	$item_categories[] = $item['category'];
}

$item_categories = array_unique($item_categories);

sort($item_categories);

?>

<?php if (current_user_can('manage_options')): ?>
	<?php
		// items that are hidden, show as inline list
		if (!empty($hidden_items)) {
			echo '<h2>Hidden Items' . '(' . count($hidden_items) . ')' . '</h2>';
			echo '<ul>';
			foreach ($hidden_items as $item) {
				echo '<li>';
				echo $item['title'];
				echo '</li>';
			}
			echo '</ul>';
		}
	?>
<?php endif; ?>


<ul useBlockProps>

	<?php foreach ($item_categories as $category): ?>

		<li class="wp-block-instawp-template-item-category">

			<div class="wp-block-instawp-template-item-category__label">
				Label
			</div>

			<h2 class="wp-block-instawp-template-item-category__title">
				<?php echo $category; ?>
			</h2>

			<p class="wp-block-instawp-template-item-category__description">
				<?php echo $item['category_description']; ?>
			</p>

			<ul class="wp-block-instawp-template-item-category__items">

				<?php foreach ($items as $item): ?>

					<?php
					// var_dump($item);
					// check if learn_more_link = current page, if so, remove it

					if (isset($item['learn_more_link'])) {
						$learn_more_link = $item['learn_more_link'];
						$current_page_link = get_permalink();

						if ($learn_more_link === $current_page_link) {
							unset($item['learn_more_link']);
						}
					}
					?>

					<?php if ($item['category'] === $category): ?>

						<li class="wp-block-instawp-template-item-category__item">

							<div class="wp-block-instawp-template-item">

								<div class="wp-block-instawp-template-item__thumbnail">
									<img src="<?php echo $item['featured_image']; ?>"
										alt="<?php echo $item['featured_image_alt']; ?>">
								</div>

								<div class="wp-block-instawp-template-item__content">
									<h3 class="wp-block-instawp-template-item__title">
										<?php echo $item['title']; ?>
									</h3>

									<p class="wp-block-instawp-template-item__description">
										<?php echo $item['description']; ?>
									</p>

								</div>

								<?php if (isset($item['preview_link']) || isset($item['learn_more_link'])): ?>	
									<div class="wp-block-instawp-template-item__footer">
										<div class="wp-block-instawp-template-item__buttons">
											<?php if (isset($item['preview_link'])): ?>
												<a class="wp-block-instawp-template-item__button"
													href="<?php echo esc_url($item['preview_link']); ?>" target="_blank">
													Preview
												</a>
											<?php endif; ?>
											<?php if (isset($item['learn_more_link'])): ?>
												<a class="wp-block-instawp-template-item__button"
													href="<?php echo esc_url($item['learn_more_link']); ?>">
													Learn More
												</a>
											<?php endif; ?>
										</div>
									</div>
								<?php endif; ?>

							</div>

						</li>

					<?php endif; ?>

				<?php endforeach; ?>
			</ul>

		</li>

	<?php endforeach; ?>

</ul>