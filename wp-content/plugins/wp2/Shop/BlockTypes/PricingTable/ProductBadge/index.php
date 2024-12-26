<?php
$parent = $context['webmultipliers/pricing-table-product'];

if (!$parent) {
	return;
}

$product_id = isset($parent['product']['value']) ? $parent['product']['value'] : null;

if (!$product_id) {
	return;
}

$product = get_post($product_id);

if (!$product) {
	return;
}

$badge = get_post_meta($product->ID, 'product_badge', true);

if (!$badge) {
	return;
}

?>

<div useBlockProps class="card-header">
	<span class="badge rounded-pill">
		<span class="h6 fw-bold text-uppercase">
			<?php echo esc_html($badge); ?>
		</span>
	</span>
</div>