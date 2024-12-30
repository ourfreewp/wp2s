<?php

$parent         = $context['webmultipliers/pricing-table-product'];
$product_id	    = isset($parent['product']['value']) ? $parent['product']['value'] : null;

$included_features_terms = rwmb_meta('product_included_features', '', $product_id);
$excluded_features_terms = rwmb_meta('product_excluded_features', '', $product_id);

$included_features = [];

if ($included_features_terms) {
	foreach ($included_features_terms as $term_id) {
		$term = get_term($term_id);
		$included_features[] = [
			'id' => $term->term_id,
			'name' => $term->name,
			'description' => $term->description
		];
	}
}

$excluded_features = [];

if ($excluded_features_terms) {
	foreach ($excluded_features_terms as $term_id) {
		$term = get_term($term_id);
		$excluded_features[] = [
			'id' => $term->term_id,
			'name' => $term->name,
			'description' => $term->description
		];
	}
}

// ensure included features don't include excluded features

if ($included_features && $excluded_features) {

	$included_features = array_filter($included_features, function ($feature) use ($excluded_features) {
		$feature_id = $feature['id'];
		$excluded_feature_ids = array_map(function ($feature) {
			return $feature['id'];
		}, $excluded_features);

		return !in_array($feature_id, $excluded_feature_ids);
	});

	$excluded_features = array_filter($excluded_features, function ($feature) use ($included_features) {
		$feature_id = $feature['id'];
		$included_feature_ids = array_map(function ($feature) {
			return $feature['id'];
		}, $included_features);

		return !in_array($feature_id, $included_feature_ids);
	});
}

?>
Test
<ul useBlockProps class="list-group list-group-flush">

	<?php if ($included_features): ?>

		<li class="list-group-item d-flex justify-content-start align-items-center py-3 lh-sm">
			What's Included:
		</li>

		<?php foreach ($included_features as $feature): ?>

			<?php
			$feature_name = $feature['name'];
			$feature_description = $feature['description'];
			?>


			<li class="list-group-item d-flex justify-content-start align-items-center py-3 lh-sm" data-bs-toggle="tooltip"
				data-bs-placement="left" data-bs-title="<?php echo esc_attr($feature_description); ?>">
				<span class="text-success">
					<i class="bi bi-check"></i>
					<span class="visually-hidden">Included</span>
				</span>

				<span class="ms-2">
					<?php echo esc_html($feature_name); ?>
				</span>

			</li>

		<?php endforeach; ?>

	<?php endif; ?>

	<?php if ($excluded_features): ?>

		<li class="list-group-item d-flex justify-content-start align-items-center py-3 lh-sm">
			What's Missing:
		</li>

		<?php foreach ($excluded_features as $feature): ?>

			<?php
			$feature_name = $feature['name'];
			$feature_description = $feature['description'];
			?>

			<li class="list-group-item d-flex justify-content-start align-items-center py-3 lh-sm" data-bs-toggle="tooltip"
				data-bs-placement="left" data-bs-title="<?php echo esc_attr($feature_description); ?>">
				<span class="text-danger">
					<i class="bi bi-x"></i>
					<span class="visually-hidden">Excluded</span>
				</span>

				<span class="ms-2">
					<?php echo esc_html($feature_name); ?>
				</span>

			</li>

		<?php endforeach; ?>

	<?php endif; ?>

</ul>